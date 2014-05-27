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
	 * @param  string | array  $paths
	 * @param  string  $class
	 * @return mixed
	 */
	public function path($paths, $class = 'active')
	{
		return $this->request->is($paths) ? $class : null;
	}

	/**
	 * Return active class if route names are matched.
	 *
	 * @param  string | array  $routes
	 * @param  string  $class
	 * @return mixed
	 */
	public function route($routes, $class = 'active')
	{
        return $this->router->is($routes) ? $class : null;
	}
}
