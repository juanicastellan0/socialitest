<?php

namespace App\Http\Controllers;

use App\User;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        $user_info = Socialite::driver($provider)->user();

        $user = $this->createUser($user_info, $provider);

        auth()->login($user);

        return redirect()->to('/home');

    }
    function createUser($user_info, $provider){

        $user = User::where('provider_id', $user_info->id)->first();

        if (!$user) {
            $user = User::create([
                'name'     => $user_info->name,
                'email'    => $user_info->email,
                'provider' => $provider,
                'provider_id' => $user_info->id
            ]);
        }
        return $user;
    }
}
