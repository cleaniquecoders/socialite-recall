# Socialite ReCall

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cleaniquecoders/socialite-recall.svg?style=flat-square)](https://packagist.org/packages/cleaniquecoders/socialite-recall)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/cleaniquecoders/socialite-recall/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cleaniquecoders/socialite-recall/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/cleaniquecoders/socialite-recall/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/cleaniquecoders/socialite-recall/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/cleaniquecoders/socialite-recall.svg?style=flat-square)](https://packagist.org/packages/cleaniquecoders/socialite-recall)

Handle Socialite Redirect, Callback and Logout Easily

## Installation

You can install the package via composer:

```bash
composer require cleaniquecoders/socialite-recall
```

Then install the package:

```bash
php artisan socialite-recall:install
```

Update the providers list and redirect paths as needed:

```php
'providers' => ['google', 'github', 'facebook'],
'redirect_after_login' => '/dashboard',
'redirect_after_logout' => '/',
```

## Usage


### Configure Your Socialite Providers

In your `.env` file, add credentials for the providers you want to support:

```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback

GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=${APP_URL}/auth/github/callback
```

And update `config/services.php` accordingly:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URI'),
],
```

---

### Authentication Routes

Your app automatically gets these routes:

* Redirect to provider:

  ```text
  GET /auth/{provider}/redirect
  ```

* Callback from provider:

  ```text
  GET /auth/{provider}/callback
  ```

* Logout user:

  ```text
  POST /auth/logout
  ```

Replace `{provider}` with any enabled provider (e.g., `google`, `github`, `facebook`).

---

### Example Buttons in Blade

```blade
<!-- Google Login -->
<a href="{{ route('socialite.redirect', ['provider' => 'google']) }}">
    <button type="button">Login with Google</button>
</a>

<!-- GitHub Login -->
<a href="{{ route('socialite.redirect', ['provider' => 'github']) }}">
    <button type="button">Login with GitHub</button>
</a>

<!-- Logout -->
<form action="{{ route('socialite.logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>
```

---

### After Login

* The package will:

  * Handle Socialite redirect and callback flow.
  * Create or update a user record (based on provider + provider ID).
  * Automatically log the user in.
* After login, the user will be redirected to the path set in:

```php
'redirect_after_login' => '/dashboard',
```

---

⚡ That’s it! You now have **one unified login system** for all Socialite providers without repeating redirect/callback logic.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nasrul Hazim Bin Mohamad](https://github.com/nasrulhazim)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
