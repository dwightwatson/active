<?php namespace Watson\Active;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class Active
{
	/**
	 * Routes to exlude.
	 *
	 * @var array
	 */
	protected $excludedRoutes = array();

	/**
	 * Routes to check.
	 *
	 * @var array
	 */
	protected $routes = array();

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

	public function __construct(Request $request, Router $router)
	{
		$this->request = $request;
		$this->router = $router;
	}

	/**
	 * Get current state.
	 *
	 * @param  string | array  $routes
	 * @return boolean
	 */
	public function is($routes)
	{
		$this->parseRoutes($routes);

		foreach ($this->routes as $route) 
		{
			// If the current route isn't the requested route, break.
			if ( ! $this->matchesPath($route)) continue;

			foreach ($this->excludedRoutes as $excludedRoute) 
			{
				// If the requested route is one of the excluded routes
				// break.
				if (str_is($excludedRoute, $this->request->path())) 
				{
					return false;
				}
			}

			// The current route matches the requested route.
			return true;
		}

		// No routes matched the requested route.
		return false;
	}

	/**
	 * Return active class if paths are matched.
	 *
	 * @param  string | array  $paths
	 * @param  string  $class
	 * @return mixed
	 */
	public function path($paths, $class = 'active')
	{
		return $this->is($paths) ? $class : null;
	}

	/**
	 * Return active class if route names are matched.
	 *
	 * @param  string | array  $route
	 * @param  string  $class
	 * @return mixed
	 */
	public function route($route, $class = 'active')
	{
		// If we're not on a named route, return null.
		if ( ! $this->router->current()) return null;

		if (is_array($route)) 
		{
			foreach ($routs as $route) 
			{
				if ($this->router->current()->getName() == $routes)
				{
					return $class;
				}	
			}
		}
		else
		{
			if ($this->router->current()->getName() == $route)
			{
				return $class;
			}	
		}

		return null;
	}

	/**
	 * Determine if the current request URI matches a pattern.
	 *
	 * @param  dynamic  string
	 * @return bool
	 */
	public function matchesPath()
	{
		foreach (func_get_args() as $pattern)
		{
			if (str_is($pattern, urldecode($this->request->path())))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Process single routes and arrays of routes.
	 * 
	 * @param  mixed  $route
	 * @return void
	 */
	protected function parseRoutes($route)
    {
    	if (is_array($route))
    	{
    		foreach ($route as $route)
    		{
    			$this->parseRoute($route);
    		}
    	}
    	else
    	{
	    	$this->parseRoute($route);
		}
    }

    /**
     * Separate excluded routes from those to be accepted.
     *
     * @param  string  $route
     * @return void
     */
    protected function parseRoute($route)
    {
    	if (Str::startsWith($route, 'not:'))
    	{
    	    $excludedRoute = substr($route, strpos($route, 'not:') + 4);

    	    $this->excludedRoutes[] = $excludedRoute;
    	}
    	else
    	{
    	    $this->routes[] = $route;
    	}
    }
}
