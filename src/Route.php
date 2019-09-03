<?php

namespace Watson\Active;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class Route
{    
    /**
     * Illuminate Router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /** 
     * Construct the class.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Get the controller name, separated as necessary and with or without namespaces.
     *
     * @param  string  $separator
     * @param  bool    $includeNamespace
     * @param  string  $trimNamespace
     * @return string|null
     */
    public function controller($separator = null, $includeNamespace = true, $trimNamespace = 'App\Http\Controllers\\')
    {
        if ($action = $this->router->currentRouteAction()) {
            $separator = is_null($separator) ? ' ' : $separator;

            $controller = head(Str::parseCallback($action, null));

            // If the controller contains the given namespace, remove it.
            if (substr($controller, 0, strlen($trimNamespace)) === $trimNamespace) {
                $controller = substr($controller, strlen($trimNamespace));
            }

            // If the controller contains 'Controller' at the end, remove it.
            if (substr($controller, - strlen('Controller')) === 'Controller') {
                $controller = substr($controller, 0, - strlen('Controller'));
            }

            // Separate out nested controller resources.
            $controller = str_replace('_', $separator, Str::snake($controller));

            // Either separate out the namespaces or remove them.
            $controller = $includeNamespace ? str_replace('\\', null, $controller) : substr(strrchr($controller, '\\'), 1);

            return trim($controller);
        }

        return null;
    }

    /**
     * Get the current controller action name.
     *
     * @param  bool  $removeHttpMethod
     * @return string|null
     */
    public function action($removeHttpMethod = true)
    {
        if ($action = $this->router->currentRouteAction()) {
            $action = last(Str::parseCallback($action, null));

            if ($removeHttpMethod) {
                $action = str_replace(['get', 'post', 'patch', 'put', 'delete'], '', $action);
            }

            return Str::snake($action, '-');
        }

        return null;
    }
}
