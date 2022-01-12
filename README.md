<p align="center">   
    <a href="https://github.com/laraditz/user-security"><img src="https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square" alt="License"></a>
    <a href="https://packagist.org/packages/laraditz/user-security"><img src="https://img.shields.io/packagist/v/laraditz/user-security?style=flat-square"/></a>
    <a href="https://packagist.org/packages/laraditz/user-security"><img src="https://img.shields.io/packagist/dt/laraditz/user-security?color=red&style=flat-square" /></a>
    <a href="https://github.com/laraditz/user-security"><img src="https://github.styleci.io/repos/7548986/shield?style=square" alt="styleci"></img></a>
</p>

# Laravel User Security

Add security pin, mnemonic key and 2fa authentication feature to users.

## Installation

Via Composer

``` bash
$ composer require laraditz/user-security
```

## Configuration

The Laravel and Lumen configurations vary slightly, so here are the instructions for each of the frameworks.

### Laravel

Edit the `config/app.php` file and add the following line to register the service provider:

```php
'providers' => [
    ...
    Laraditz\UserSecurity\UserSecurityServiceProvider::class,
    ...
],
```

> Tip: If you're on Laravel version **5.5** or higher, you can skip this part of the setup in favour of the Auto-Discovery feature.

### Lumen

Edit the `bootstrap/app.php` file and add the following line to register the service provider:

```php
...
$app->register(Laraditz\UserSecurity\UserSecurityServiceProvider::class);
...
```

You will also need to enable `Facades`  in `bootstrap/app.php`:

```php
..
$app->withFacades(true, [
    ...
    Laraditz\UserSecurity\Facades\SecureUser::class => 'SecureUser'
]);
...
```

Open your user provider model class, for example `App\Models\User`, and add `Laraditz\UserSecurity\Traits\UserSecurable` trait:

```php
<?php

namespace App;

...
use Laraditz\UserSecurity\Traits\UserSecurable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    ...
    use UserSecurable;
    ...
}
```

Add a key to your `.env` file for hashing.
```
LUS_KEY=set_your_key_here // for one-way encrypt, do not lose or change this key after set.
```

Add validation rule message to `resources/lang/{lang_code}/validation.php`.
```php
...
'mnemonic' => 'The :attribute is invalid.',
'mnemonic_exists' => 'The :attribute is already been used.',
...
```

## Usage

Example usage as below snippet:

```php
// to add/update security pin for eloquent user
$user->updateSecurityPin($security_pin);

// to add/update entropy for eloquent user
$user->updateEntropy($entropy);

// to add/update multiple authenticators
$user->updateMultipleAuthenticators(['security_pin' => $security_pin, 'mnemonic_entropy' => $entropy]);
```

To use mnemonic functions, examples as below:

```php
// Success response

// using service container to generate mnemonic object
$mnemonic = app('SecureUser')->mnemonic()->generate();

// using alias to generate mnemonic object
$mnemonic = \SecureUser::mnemonic()->generate();

// Use mnemonic codes to find entropy
$mnemonic = \SecureUser::mnemonic()->words($words);

// Generate Mnemonic using specified Entropy
$mnemonic = \SecureUser::mnemonic()->entropy($entropy);

// Get user by mnemonic words
$user = \SecureUser::mnemonic()->userByWords($words);
```

It also comes with `mnemonic` and `mnemonic_exists` rules:
- mnemonic - to check whether `mnemonic_words` and `mnemonic_entropy` match.
- mnemonic_exists - to check whether `mnemonic_words` or `mnemonic_entropy` already exists.

```php
$this->validate($request, [
    ...
    'mnemonic_words' => 'required|array|mnemonic',
    'mnemonic_entropy' => 'required|mnemonic_exists',
    ...
]);
```

For security pin, you can use `MatchSecurityPin` rule. You can pass the model in the constructor. Otherwise, the rule will assume you want to use the session user. The model must use `UserSecurable` trait.

```php
use Laraditz\UserSecurity\Rules\MatchSecurityPin;


$this->validate($request, [
    ...
    'security_pin' => new MatchSecurityPin,
    'security_pin2' => new MatchSecurityPin($model),
    ...
]);
```


## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Raditz Farhan](https://github.com/raditzfarhan)

## License

MIT. Please see the [license file](LICENSE) for more information.