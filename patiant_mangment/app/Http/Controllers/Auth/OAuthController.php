<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OAuthController extends Controller
{
    // Redirect to the OAuth2 provider
    public function redirectToProvider()
    {
        return Socialite::driver('zoho')->redirect();
    }

    // Handle the OAuth2 callback
    public function handleProviderCallback(): Redirector|RedirectResponse
    {
        $oauthUser = Socialite::driver('zoho')->user();

        // Find or create the user in your database
        $user = User::firstOrCreate(
            ['email' => $oauthUser->getEmail()],
            [
                'name' => $oauthUser->getName(),
                'password' => bcrypt(uniqid()), // Random password
            ]
        );

        // Log in the user
        Auth::login($user, true);

        // Redirect to the Filament dashboard
        return redirect('/admin');
    }
}
