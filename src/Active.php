<?php

namespace Watson\Active;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Config\Repository;

class Active
{
    /**
     * Illuminate Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Illuminate Router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Illuminate Config instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Construct the class.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param  \Illuminate\Routing\Router     $router
     * @param  \Illuminate\Config\Repository  $config
     * @return void
     */
    public function __construct(Request $request, Router $router, Repository $config)
    {
        $this->request = $request;
        $this->router = $router;
        $this->config = $config;
    }

    /**
     * Determine if any of the provided routes are active.
     *
     * @param  mixed  $routes
     * @return bool
     */
    public function isActive($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        list($routes, $ignoredRoutes) = $this->parseIgnoredRoutes($routes);

        if ($this->isPath($routes) || $this->isFullPath($routes) || $this->isRoute($routes)) {
            if (count($ignoredRoutes) && ($this->isPath($ignoredRoutes) || $this->isFullPath($routes) || $this->isRoute($ignoredRoutes))) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get the active class if the active path is provided.
     *
     * @param  mixed $routes
     * @param  string $class
     * @param  null  $fallbackClass
     * @return string|null
     */
    public function active($routes, $class = null, $fallbackClass = null)
    {
        $routes = (array) $routes;

        if ($this->isActive($routes)) {
            return $this->getActiveClass($class);
        }

        if ($fallbackClass) {
            return $fallbackClass;
        }
    }

    /**
     * Determine if the current path is one of the provided paths.
     *
     * @param  mixed   $routes
     * @return boolean
     */
    public function isPath($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return call_user_func_array([$this->request, 'is'], $routes);
    }

    /**
     * Determine if the current full path is one of the provided paths.
     *
     * @param  mixed   $routes
     * @return boolean
     */
    public function isFullPath($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return call_user_func_array([$this->request, 'fullUrlIs'], $routes);
    }

    /**
     * Get the active class if the active path is provided.
     *
     * @param  mixed   $routes
     * @param  string  $class
     * @return string|null
     */
    public function path($routes, $class = null)
    {
        $routes = (array) $routes;

        if ($this->isPath($routes)) {
            return $this->getActiveClass($class);
        }
    }

    /**
     * Determin if the current route is one of the provided routes.
     *
     * @param  mixed  $routes
     * @return boolean
     */
    public function isRoute($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return call_user_func_array([$this->router, 'is'], $routes);
    }

    /**
     * Get the active class if the active route is provided.
     *
     * @param  mixed   $routes
     * @param  string  $class
     * @return string|null
     */
    public function route($routes, $class = null)
    {
        $routes = (array) $routes;

        if ($this->isRoute($routes)) {
            return $this->getActiveClass($class);
        }
    }

    /**
     * Return the active class if it is provided, otherwise fall back
     * to the class set in the configuration.
     *
     * @param  string  $class
     * @return string
     */
    protected function getActiveClass($class = null)
    {
        return $class ?: $this->config->get('active.class');
    }

    /**
     * Separate ignored routes from the provided routes.
     *
     * @param  mixed  $routes
     * @return array
     */
    private function parseIgnoredRoutes($routes)
    {
        $ignoredRoutes = [];

        $routes = is_array($routes) ? $routes : func_get_args();

        foreach ($routes as $index => $route) {
            if (Str::startsWith($route, 'not:')) {
                $ignoredRoute = substr($route, 4);

                unset($routes[$index]);

                $ignoredRoutes[] = $ignoredRoute;
            }
        }

        return [$routes, $ignoredRoutes];
    }
}
