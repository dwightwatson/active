<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if ( ! function_exists('controller_name'))
{
	function controller_name()
	{
        if ($action = Route::currentRouteAction())
        {
            $controller = head(Str::parseCallback($action, null));

            // Remove 'controller' from the controller name.
            return Str::lower(str_replace('Controller', '', $controller));
        }

        return null;
	}
}

if ( ! function_exists('action_name'))
{
	function action_name()
	{
        if ($action = Route::currentRouteAction())
        {
            $action = last(Str::parseCallback($action, null));

            // Take out the method from the action.
            return Str::lower(str_replace(array('get', 'post', 'patch', 'put', 'delete'), '', $action));
        }

        return null;
	}
}