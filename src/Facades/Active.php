<?php 

namespace Watson\Active\Facades;

use Illuminate\Support\Facades\Facade;

class Active extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'active';
    }
}
