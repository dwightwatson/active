<?php

use Watson\Active\Route;
use Illuminate\Support\Facades\App;

if ( ! function_exists('controller_name')) {
    /**
     * Get the controller name, separated as necessary and with or without namespaces.
     *
     * @param  string  $separator
     * @param  bool    $includeNamespace
     * @param  string  $trimNamespace
     * @return string|null
     */
    function controller_name($separator = null, $includeNamespace = true, $trimNamespace = 'App\Http\Controllers\\')
    {
        return App::make(Route::class)->controller($separator, $includeNamespace, $trimNamespace);
    }
}

if ( ! function_exists('action_name')) {
    /**
     * Get the current controller action name.
     *
     * @param  bool  $removeHttpMethod
     * @return string|null
     */
    function action_name($removeHttpMethod = true)
    {
        return App::make(Route::class)->action($removeHttpMethod);
    }
}

if ( ! function_exists('active')) {
    /**
     * Get the active class if an active path is provided.
     *
     * @param  mixed $routes
     * @param  string $class
     * @param  null  $fallbackClass
     * @return string|null
     */
    function active($routes = null, $class = null, $fallbackClass = null)
    {
        if (is_null($routes)) {
            return App::make('active');
        }

        $routes = is_array($routes) ? $routes : [$routes];

        return active()->active($routes, $class, $fallbackClass);
    }
}

if ( ! function_exists('is_active')) {
    /**
     * Determine if any of the provided routes are active.
     *
     * @param  mixed  $routes
     * @return bool
     */
    function is_active($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return active()->isActive($routes);
    }
}
