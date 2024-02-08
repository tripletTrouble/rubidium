<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SingleSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SingleSignController extends Controller
{
    /**
     * Login attemp, we try to reach portal oauth
     * 
     */
    public function login()
    {
        return redirect(SingleSign::prepare()->generateCodeUrl());
    }

    /**
     * Getting token, we try to get token then save it into
     * authenticated user's session
     * 
     */
    public function auth(Request $request)
    {
        SingleSign::validateState($request->state) ;

        $response = SingleSign::prepare()->makeTokenRequest($request->code);
        $response = $response->json();

        $portal_user = SingleSign::prepare()->getUser($response['access_token']);
        $portal_user = $portal_user->json();

        $user = User::updateOrCreate([
            'email' => $portal_user['email']
        ], [
            'name' => $portal_user['name']
        ]);

        unset($response['token_type']);

        $user->portalToken()->updateOrCreate(['user_id' => $user->id], $response);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
