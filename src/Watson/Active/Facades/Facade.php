<?php namespace Watson\Active\Facades;

use Illuminate\Support\Facades\Facade;

class Active extends Facade
{
	protected static function getFacadeAccessor() { return 'active'; }
}