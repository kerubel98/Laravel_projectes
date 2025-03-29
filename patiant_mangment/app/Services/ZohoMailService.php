<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoMailService
{
    protected $baseUrl = 'https://mail.zoho.com/api/accounts/me';

    public function getInboxEmails($page = 1, $perPage = 15)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . auth()->user()->zoho_access_token,
            ])->get("$this->baseUrl/messages", [
                'limit' => $perPage,
                'start' => ($page - 1) * $perPage,
                'sortOrder' => 'desc'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Zoho API Error: ' . $response->body());
            return ['messages' => [], 'total' => 0];

        } catch (\Exception $e) {
            Log::error('Zoho Service Exception: ' . $e->getMessage());
            return ['messages' => [], 'total' => 0];
        }
    }

    public function refreshAccessToken()
    {
        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'refresh_token' => auth()->user()->zoho_refresh_token,
            'grant_type' => 'refresh_token'
        ]);

        if ($response->successful()) {
            auth()->user()->update([
                'zoho_access_token' => $response->json()['access_token'],
                'zoho_expires_at' => now()->addSeconds($response->json()['expires_in'])
            ]);
            return true;
        }

        return false;
    }
}
