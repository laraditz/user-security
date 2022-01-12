<?php

namespace Laraditz\UserSecurity;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class UserSecurityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/usersecurity.php' => $this->configPath('usersecurity.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        Validator::extend('mnemonic', '\Laraditz\UserSecurity\Rules\ExtendedValidator@mnemonic');
        Validator::extend('mnemonic_exists', '\Laraditz\UserSecurity\Rules\ExtendedValidator@mnemonicExists');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/usersecurity.php',
            'usersecurity'
        );

        // Register the service the package provides.
        $this->app->singleton('SecureUser', function ($app) {
            return new SecureUser;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['SecureUser'];
    }

    public function configPath($file)
    {
        if (function_exists('config_path')) {
            return config_path($file);
        } else {
            return base_path($file);
        }
    }
}
