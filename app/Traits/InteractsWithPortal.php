<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;

trait InteractsWithPortal
{
    public function getRoles()
    {
        $response = Http::withHeaders([
            "Authorization" => "Bearer {$this->portalToken->access_token}",
            "Accept" => "application/json"
        ])->get(env("PORTAL_SERVER")."/api/user/roles");

        return $response->json();
    }

    public function hasRole(string $role): bool
    {
        $roles = $this->getRoles();

        return in_array($role, $roles);
    }
}
