<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if ( ! function_exists('controller_name'))
{
	function controller_name($includeNamespace = true)
	{
        if ($action = Route::currentRouteAction())
        {
            $controller = head(Str::parseCallback($action, null));

            // Remove Controller from the controller name.
            $controller = str_replace('Controller', '', $controller);

            // Separate out nested controller resources.
            $controller = str_replace('_', ' ', snake_case($controller));

            // Either separate out the namespaces or remove them.
            $controller = $includeNamespace ? str_replace('\\', '', $controller) : substr(strrchr($controller, '\\'), 1);

            // Remove 'controller' from the controller name.
            return trim($controller);
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