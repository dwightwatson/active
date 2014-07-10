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
	 * Return active class if paths are matched.
	 *
	 * @param  mixed  $paths
	 * @param  string $class
	 * @return mixed
	 */
	public function path($paths, $class = 'active')
	{
		return call_user_func_array([$this->request, 'is'], (arraY) $paths) ? $class : null;
	}

	/**
	 * Return active class if route names are matched.
	 *
	 * @param  mixed  $routes
	 * @param  string $class
	 * @return mixed
	 */
	public function route($routes, $class = 'active')
	{
		return call_user_func_array([$this->router, 'is'], (array) $routes) ? $class : null;
	}

	/**
	 * Return active class if route name and identifier is matched.
	 *
	 * @param  string $route
	 * @param  string $identifier
	 * @param  string $attribute
	 * @param  string $class
	 * @return mixed
	 */
	public function resource($route, $identifier, $attribute = 'id', $class = 'active')
	{
		if ($this->route($route))
		{
			if ($this->router->current()->parameters($attribute) == $identifier)
			{
				return $class;
			}
		}
	}
}
