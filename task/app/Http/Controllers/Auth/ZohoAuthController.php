<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message; // Add Message model
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // Add HTTP client

class ZohoAuthController extends Controller
{
    public function redirectToZoho()
    {
        return Socialite::driver('zoho')
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent'
            ])
            ->scopes([
                'ZohoMail.accounts.READ', // Added mail scopes
                'ZohoMail.messages.READ',
            ])
            ->redirect();
    }

    public function handleZohoCallback(): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|RedirectResponse
    {
        try {
            $zohoUser = Socialite::driver('zoho')->user();
            DB::beginTransaction();
            $user = User::where('email', $zohoUser->email)->first();

            if (!$user) {
                try {
                    $user = User::create([
                        'name' => $zohoUser->name,
                        'email' => $zohoUser->email,
                        'zoho_id' => $zohoUser->id,
                        'zoho_token' => $zohoUser->token,
                        'zoho_refresh_token' => $zohoUser->refreshToken,
                    ]);
                } catch (\Exception $exception) {
                    dd($exception->getMessage());
                }

            } else {

                $user->update([
                    'zoho_id' => $zohoUser->id,
                    'zoho_token' => $zohoUser->token,
                    'zoho_refresh_token' => $zohoUser->refreshToken,
                ]);
            }

            DB::commit();

            auth()->login($user, true);
            return redirect()->intended('/admin');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Zoho Auth Error: ' . $e->getMessage());
            return redirect('/admin')->withErrors(['zoho' => 'Authentication failed']);
        }
    }

    public function fetchAndStoreInbox(): RedirectResponse
    {
        try
        {
            $user = auth()->user();

            $accountResponse = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $user->zoho_token,
            ])->get('https://mail.zoho.com/api/accounts');

            if ($accountResponse->failed()) {
                $this->refreshToken($user);
                return redirect()->back()->withErrors(['token' => 'Token expired']);
            }


            $accountId = $accountResponse->json()['data'][0]['accountId'];
            $limit = 20;

            $start = Message::where('user_id', $user->id)->count();
            $latestMessage = Message::where('user_id', $user->id)
                ->orderBy('received_at', 'desc') // Sort by received_at in descending order
                ->first();
            if ($latestMessage) {
                $unixTimestamp = $latestMessage->received_at->timestamp * 1000; // Unix timestamp in seconds
            } else {
                $unixTimestamp = Carbon::today()->timestamp;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $user->zoho_token,
            ])->get("https://mail.zoho.com/api/accounts/{$accountId}/messages/search", [
                'searchKey' => 'newMails',
                'receivedTime' => $unixTimestamp,
            ]);

            if ($response->failed()) {
                logger()->error('Zoho API Error: ' . $response->body());
                return redirect()->back()->withErrors(['error' => 'Failed to fetch messages']);
            }
            $messages = $response->json()['data'] ?? [];

            DB::transaction(function () use ($messages, $user) {
                foreach ($messages as $index => $msg) {
                    try {

                        if (empty($msg['messageId']) || empty($msg['fromAddress'])) {
                            logger()->warning('Skipping message: Missing required fields', ['index' => $index]);
                            continue;
                        }

                        $toAddress = html_entity_decode($msg['toAddress']);
                        $ccAddress = html_entity_decode($msg['ccAddress']);


                        preg_match_all('/<([^>]+)>/', $toAddress, $toMatches);
                        preg_match_all('/<([^>]+)>/', $ccAddress, $ccMatches);
                        $toEmails = $toMatches[1] ?? [];
                        $ccEmails = $ccMatches[1] ?? [];
                        $recipientType = null;
                        if (in_array($user->email, $toEmails)) {
                            $recipientType = 'to';
                        } elseif (in_array($user->email, $ccEmails)) {
                            $recipientType = 'cc';
                        }


                        Message::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'zoho_message_id' => $msg['messageId'],
                                'subject' => $msg['subject'] ?? 'No Subject',
                                'sender' => $msg['fromAddress'],
                                'content' => $msg['summary'] ?? '',
                                'to_address' => $toAddress,
                                'cc_address' => $ccAddress,
                                'priority' => (int)($msg['priority'] ?? 3),
                                'thread_id' => $msg['threadId'] ?? null,
                                'flag_id' => $msg['flagid'] ?? 'flag_not_set',
                                'recipient_type' => $recipientType,
                                'received_at' => isset($msg['receivedTime'])
                                    ? Carbon::createFromTimestampMs($msg['receivedTime'])
                                    : now(),
                            ]
                        );

                    } catch (\Exception $e) {
                        logger()->error("Failed to process message at index $index", [
                            'message' => $msg,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            });

            return redirect()->back()->with('success', count($messages) . ' messages synced!');

        } catch (\Exception $e) {
            logger()->error('Inbox Sync Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to sync inbox']);
            }
    }

    private function refreshToken($user): void
    {

        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'refresh_token' => $user->zoho_refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $user->update(['zoho_token' => $response->json()['access_token']]);
        } else {
            logger()->error('Token Refresh Failed: ' . $response->body());
        }
    }
}
