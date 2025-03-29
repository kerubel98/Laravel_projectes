<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class ZohoController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('zoho')
            ->scopes(['ZohoMail.messages.READ'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            // Ensure session is available

            if (!$request->hasSession()) {
                abort(500, 'Session store not set');
            }

            $zohoUser = Socialite::driver('zoho')->user();

            $user = User::find(auth()->id());
            $user->update([
                'zoho_access_token' => $zohoUser->token,
                'zoho_refresh_token' => $zohoUser->refreshToken,
                'zoho_expires_at' => now()->addSeconds($zohoUser->expiresIn),
            ]);

            return redirect()->route('filament.admin.resources.inboxes.index')
                ->with('success', 'Successfully connected to Zoho!');

        } catch (\Exception $e) {
            logger()->error('Zoho Connection Error: ' . $e->getMessage());
            return redirect()->route('filament.admin.resources.inboxes.index')
                ->with('error', 'Connection failed: ' . $e->getMessage());
        }
    }
}
