<?php

namespace Laraditz\UserSecurity\Facades;

use Illuminate\Support\Facades\Facade;

class SecureUser extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'SecureUser';
    }
}
