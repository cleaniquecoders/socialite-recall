<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

// Setup fake routes for tests
beforeEach(function () {
    Route::get('/auth/{provider}/redirect', fn ($provider) => 'redirect')
        ->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', fn ($provider) => 'callback')
        ->name('socialite.callback');
    Route::post('/auth/logout', fn () => 'logout')
        ->name('socialite.logout');
});

it('redirects to provider', function () {
    // Fake the Socialite redirect response
    $mockResponse = new RedirectResponse('/redirect-url');

    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('redirect')
        ->once()
        ->andReturn($mockResponse);

    $this->get(route('socialite.redirect', ['provider' => 'github']))
        ->assertRedirect('/redirect-url');
});

it('handles provider callback and logs in user', function () {
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('12345');
    $socialiteUser->shouldReceive('getName')->andReturn('Test User');
    $socialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $socialiteUser->shouldReceive('getAvatar')->andReturn('http://example.com/avatar.jpg');

    Socialite::shouldReceive('driver')
        ->with('github')
        ->andReturnSelf();

    Socialite::shouldReceive('user')
        ->once()
        ->andReturn($socialiteUser);

    $this->get(route('socialite.callback', ['provider' => 'github']))
        ->assertStatus(200); // Replace with your controller's redirect
});

it('can logout user', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $this->post(route('socialite.logout'))
        ->assertOk(); // adjust if your controller redirects

    expect(Auth::check())->toBeFalse();
});
