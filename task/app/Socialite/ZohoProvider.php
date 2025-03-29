<?php

namespace App\Socialite;


use Illuminate\Http\Request;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class ZohoProvider extends AbstractProvider
{
    protected mixed $accountsUrl;
    protected $scopes = [
        'aaaserver.profile.READ',
        'ZohoMail.accounts.READ',
        'ZohoMail.messages.ALL',
    ];

    protected $scopeSeparator = ' ';

    public function __construct(
        Request $request,
        string $clientId,
        string $clientSecret,
        string $redirectUrl,
        array $config
    ) {
        parent::__construct(
            $request,
            $clientId,
            $clientSecret,
            $redirectUrl,
            $config['guzzle'] ?? []
        );

        $this->accountsUrl = $config['accounts_url'];
    }


    public function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            "{$this->accountsUrl}/oauth/v2/auth",
            $state
        );
    }

    // 2. Token URL (Protected)
    protected function getTokenUrl(): string
    {
        return "{$this->accountsUrl}/oauth/v2/token";
    }

    // 3. User By Token (Protected)
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            "{$this->accountsUrl}/oauth/user/info",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }


    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['ZUID'],
            'name'     => $user['Display_Name'] ?? $user['Email'],
            'email'    => $user['Email'],
            'first_name' => $user['First_Name'] ?? null,
            'last_name'  => $user['Last_Name'] ?? null,
        ]);
    }

    // 5. Token Fields (Optional but recommended)
    protected function getTokenFields($code): array
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
