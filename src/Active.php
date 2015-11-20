<?php 

namespace Watson\Active;

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
     * Construct the class.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
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

        if ($this->isPath($routes) || $this->isRoute($routes)) {
            if (count($ignoredRoutes) && ($this->isPath($ignoredRoutes) || $this->isRoute($ignoredRoutes))) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get the active class if the active path is provided.
     *
     * @param  mixed   $routes
     * @param  string  $class
     * @return string|null
     */
    public function active($routes, $class = 'active')
    {
        $routes = (array) $routes;

        return $this->isActive($routes) ? $class : null;
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
     * Get the active class if the active path is provided.
     *
     * @param  mixed   $routes
     * @param  string  $class
     * @return string|null
     */
    public function path($routes, $class = 'active')
    {
        $routes = (array) $routes;

        return $this->isPath($routes) ? $class : null;
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
    public function route($routes, $class = 'active')
    {
        $routes = (array) $routes;

        return $this->isRoute($routes) ? $class : null;
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
