<?php

namespace Watson\Active;

use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;

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
     * @param  \Illuminate\Http\Request      $request
     * @param  \Illuminate\Routing\Router    $router
     * @param  \Illuminate\Config\Repository $config
     * @return void
     */
    public function __construct(Request $request, Router $router, Repository $config)
    {
        $this->request = $request;
        $this->router = $router;
        $this->config = $config;
    }

    /**
     * Get the active class if the active path is provided.
     *
     * @param  mixed  $routes
     * @param  string $class
     * @return string|null
     */
    public function active($routes, $class = null)
    {
        $routes = (array) $routes;

        if ($this->isActive($routes)) {
            return $this->getActiveClass($class);
        }
    }

    /**
     * Determine if any of the provided routes are active.
     *
     * @param  mixed $routes
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
     * Separate ignored routes from the provided routes.
     *
     * @param  mixed $routes
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

    /**
     * Determine if the current path is one of the provided paths.
     *
     * @param  mixed $routes
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
     * @param  mixed $routes
     * @return boolean
     */
    public function isFullPath($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return call_user_func_array([$this->request, 'fullUrlIs'], $routes);
    }

    /**
     * Determin if the current route is one of the provided routes.
     *
     * @param  mixed $routes
     * @return boolean
     */
    public function isRoute($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return call_user_func_array([$this->router, 'is'], $routes);
    }

    /**
     * Return the active class if it is provided, otherwise fall back
     * to the class set in the configuration.
     *
     * @param  string $class
     * @return string
     */
    protected function getActiveClass($class = null)
    {
        return $class ?: $this->config->get('active.class');
    }

    /**
     * Get the active class if the active path is provided.
     *
     * @param  mixed  $routes
     * @param  string $class
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
     * Get the active class if the active route is provided.
     *
     * @param  mixed  $routes
     * @param  string $class
     * @return string|null
     */
    public function route($routes, $class = null)
    {
        $routes = (array) $routes;

        if ($this->isRoute($routes)) {
            return $this->getActiveClass($class);
        }
    }
}
