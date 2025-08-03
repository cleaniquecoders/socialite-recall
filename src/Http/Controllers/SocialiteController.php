<?php

namespace CleaniqueCoders\SocialiteRecall\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        if (! in_array($provider, config('socialite-recall.providers', []))) {
            abort(404, 'Provider not supported.');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        if (! in_array($provider, config('socialite-recall.providers', []))) {
            abort(404, 'Provider not supported.');
        }

        try {
            $socialiteUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'provider' => 'Failed to authenticate with '.ucfirst($provider),
            ]);
        }

        $userModel = config('socialite-recall.providers.model');
        $user = $userModel::updateOrCreate(
            [
                'email' => $socialiteUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
            ],
            [
                'name' => $socialiteUser->getName() ?? $socialiteUser->getNickname(),
                'avatar' => $socialiteUser->getAvatar(),
            ]
        );

        Auth::login($user, true);

        return redirect()->intended(config('socialite-recall.providers.redirect_after_login', '/'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        $provider = $user->provider;

        if (! in_array($provider, config('socialite-recall.providers', []))) {
            abort(404, 'Provider not supported.');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // REVIEW - There might be different approach of logout, depends on provider.
        return redirect(
            Socialite::driver($provider)
                ->getLogoutUrl(
                    config('socialite-recall.providers.redirect_after_logout', '/'),
                    config('services.'.$provider.'.client_id')
                )
        );
    }
}
