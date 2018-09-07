<?php

use Watson\Active\Route;
use Illuminate\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    protected $route;

    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->router = Mockery::mock(Router::class);

        $this->route = new Route($this->router);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function controller_gets_controller_name()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('App\Http\Controllers\FooController@bar');

        $result = $this->route->controller();

        $this->assertEquals('foo', $result, "Does not get correct controller name.");
    }

    /** @test */
    public function controller_gets_namespaced_controller_name()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('App\Http\Controllers\Baz\FooController@bar');

        $result = $this->route->controller();

        $this->assertEquals('baz foo', $result, "Does not get the correct namespaced controller name.");
    }

    /** @test */
    public function controller_gets_namespaced_controller_with_separator()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('App\Http\Controllers\Baz\FooController@bar');

        $result = $this->route->controller('-');

        $this->assertEquals('baz-foo', $result, "Does not get the correct separated namespaced controller name.");
    }

    /** @test */
    public function controller_gets_controller_without_namespace()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('App\Http\Controllers\Baz\FooController@bar');

        $result = $this->route->controller(null, false);

        $this->assertEquals('foo', $result, "Does not get controller name without namespace.");
    }

    /** @test */
    public function controller_gets_controller_with_alternate_namespace()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('Platform\Controllers\Baz\FooController@bar');

        $result = $this->route->controller(null, true, 'Platform\Controllers');

        $this->assertEquals('baz foo', $result, "Does not trim alternate namespace from controller.");
    }

    /** @test */
    public function controller_returns_null_when_not_on_controller()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn(null);

        $result = $this->route->controller();

        $this->assertNull($result, "Does not return null when not on controller route.");
    }


    /** @test */
    public function action_gets_action_name()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('FooController@bar');

        $result = $this->route->action();

        $this->assertEquals('bar', $result, "Does not get correct action name.");
    }

    /** @test */
    public function action_gets_kebab_case()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('FooController@barBaz');

        $result = $this->route->action();

        $this->assertEquals('bar-baz', $result, "Does not get correct action name.");
    }

    /** @test */
    public function action_removes_http_method()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('FooController@getBar');

        $result = $this->route->action(true);

        $this->assertEquals('bar', $result, "Does not remove the method from the action.");
    }

    /** @test */
    public function action_does_not_remove_http_method()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn('FooController@getBar');

        $result = $this->route->action(false);

        $this->assertEquals('get-bar', $result, "Removes the method from the action.");
    }

    /** @test */
    public function action_returns_null_when_not_on_controller()
    {
        $this->router->shouldReceive('currentRouteAction')->once()->andReturn(null);

        $result = $this->route->action();

        $this->assertNull($result, "Does not return null when not on controller route.");
    }
}
