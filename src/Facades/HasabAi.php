<?php

namespace Axumoss\HasabAi\Facades;

use Illuminate\Support\Facades\Facade;

class HasabAi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hasabai';
    }
}
