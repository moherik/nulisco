<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
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

        return response()->json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ]);
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
                'error' => 'Invalid credentials provider.'
            ]);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => Carbon::now(),
                'name' => $user->getName(),
                'avatar' => $user->getAvatar(),
            ]
        );

        if ($userCreated->wasRecentlyCreated) {
            $userCreated->update(['username' => Str::slug($userCreated->name, '') . $userCreated->id]);
        }

        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId()
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $newUser = Auth::loginUsingId($userCreated->id, true);

        return new UserResource($newUser);
    }

    /**
     * Signing out authenticated user
     * 
     * @return void
     */
    public function logout()
    {
        Auth::logout();
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
