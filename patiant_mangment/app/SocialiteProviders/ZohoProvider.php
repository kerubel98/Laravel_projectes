<?php

namespace App\SocialiteProviders;

use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class ZohoProvider extends AbstractProvider implements ProviderInterface
{
    protected $accessToken;
    protected $scopes = ['aaaserver.profile.READ'];
    public function __construct()
    {
        $this->accessToken = auth()->user()->zoho_access_token;
    }


    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://accounts.zoho.com/oauth/v2/auth', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://accounts.zoho.com/oauth/v2/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://accounts.zoho.com/oauth/user/info', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        $response = $this->getHttpClient()->get('https://accounts.zoho.com/oauth/user/info', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $user = json_decode($response->getBody(), true);


        // Log the response for debugging
        \Log::info('Zoho API Response:', $user);

        return $user;


    }

    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['ZUID'],
            'name' => $user['Display_Name'],
            'email' => $user['Email'],
        ]);
    }
    public function getInboxEmails($page = 1, $perPage = 15)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->accessToken,
        ])->get('https://mail.zoho.com/api/accounts/me/messages', [
            'limit' => $perPage,
            'start' => ($page - 1) * $perPage,
            'sortOrder' => 'desc'
        ]);

        return $response->json()['data'];
    }

}
