<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ConstantsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'constants';
    }
}
