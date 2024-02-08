<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;

class SingleSign
{
    protected string $client_id = '';
    protected string $client_secret = '';
    protected string $redirection = '';
    protected string $oauth_server = '';
    protected string $scope = 'read-profile';

    public function __construct()
    {
        $this->client_id = env('PORTAL_CLIENT_ID', 1);
        $this->client_secret = env('PORTAL_CLIENT_SECRET', 1);
        $this->redirection = env('PORTAL_REDIRECTION', 1);
        $this->oauth_server = env('PORTAL_SERVER', 1);
    }

    public static function prepare(): self
    {
        return new self();
    }

    public function generateCodeUrl(): string
    {
        // Create state and save into session
        request()->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirection,
            'response_type' => 'code',
            'scope' => $this->scope,
            'state' => $state
        ]);

        return "{$this->oauth_server}/oauth/authorize?{$query}";
    }

    public function makeTokenRequest(string $code)
    {
        return Http::asForm()->post("{$this->oauth_server}/oauth/token", [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirection,
            'code' => $code,
        ]);
    }

    public static function validateState(string $state)
    {
        throw_unless(
            strlen($state) > 0 && $state === request()->session()->pull('state'),
            PreconditionRequiredHttpException::class,
            'State is invalid.'
        );
    }

    public function getUser(string $token)
    {
        return Http::withHeaders([
            'Authorization' => "Bearer $token"
        ])->get("{$this->oauth_server}/api/user");
    }
}