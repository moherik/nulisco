<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @return \Laravel\Socialite\SocialiteManager
     */
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json([
                'error' => 'Invalid credentials provided.'
            ]);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => Carbon::now(),
                'name' => $user->getName(),
            ]
        );

        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId()
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $token = $userCreated->createToken($provider)->plainTextToken;

        return response()->json($token, 200);
    }

    /**
     * Validate provider
     * 
     * @return \Illuminate\Http\Response
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, config('services.providers'))) {
            return response()->json([
                'error' => 'Please login using facebook, twitter, facebook'
            ], 422);
        }
    }
}
