<?php

use Watson\Active\Active;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class ActiveTest extends PHPUnit_Framework_TestCase
{
    protected $active;

    protected $request;

    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);

        $this->router = Mockery::mock(Router::class);

        $this->active = new Active($this->request, $this->router);
    }

    public function tearDown()
    {
        parent::tearDown();
        
        Mockery::close();
    }

    /** @test */
    public function is_active_returns_true_when_on_path()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(true);

        $result = $this->active->isActive('foo');

        $this->assertTrue($result, "False returned when current path provided.");
    }

    /** @test */
    public function is_active_returns_true_when_on_route()
    {
        $this->request->shouldReceive('is')->with('home')->andReturn(false);

        $this->router->shouldReceive('is')->with('home')->andReturn(true);

        $result = $this->active->isActive('home');

        $this->assertTrue($result, "False returned when current route provided.");
    }

    /** @test */
    public function is_active_returns_false_when_on_ignored_path()
    {
        $this->request->shouldReceive('is')->with('foo/*')->andReturn(true);

        $this->request->shouldReceive('is')->with('foo/bar')->andReturn(true);

        $result = $this->active->isActive('foo/*', 'not:foo/bar');

        $this->assertFalse($result, "Returned true when on an ignored path.");
    }

    /** @test */
    public function is_active_returns_false_when_on_ignored_route()
    {
        $this->request->shouldReceive('is')->with('pages.*')->andReturn(false);

        $this->request->shouldReceive('is')->with('pages.show')->andReturn(false);

        $this->router->shouldReceive('is')->with('pages.*')->andReturn(true);

        $this->router->shouldReceive('is')->with('pages.show')->andReturn(true);

        $result = $this->active->isActive('pages.*', 'not:pages.show');

        $this->assertFalse($result, "Returned true when on an ignored route.");
    }

    /** @test */
    public function is_active_returns_false_when_not_on_path_or_route()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(false);

        $this->router->shouldReceive('is')->with('foo')->andReturn(false);

        $result = $this->active->isActive('foo');

        $this->assertFalse($result, "Returned true when the current route or path is not provided.");
    }


    /** @test */
    public function active_returns_active_when_on_path()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(true);

        $this->request->shouldReceive('is')->with()->andReturn(false);

        $result = $this->active->active('foo');

        $this->assertEquals('active', $result, "Wrong string returned when current path provided.");
    }

    /** @test */
    public function active_returns_provided_string_when_on_path()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(true);

        $this->request->shouldReceive('is')->with()->andReturn(false);

        $result = $this->active->active('foo', 'bar');

        $this->assertEquals('bar', $result, "Wrong string returned when current path provided.");
    }

    /** @test */
    public function active_returns_null_when_not_on_path()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(false);

        $this->router->shouldReceive('is')->with('foo')->andReturn(false);

        $result = $this->active->active('foo');

        $this->assertNull($result, "Returned string when the current route or path is not provided.");    
    }


    /** @test */
    public function path_returns_active_when_on_path()
    {
        $this->request->shouldReceive('is')->andReturn(true);

        $result = $this->active->path('foo');

        $this->assertEquals('active', $result, "Class is not returned when path is matched.");
    }

    /** @test */
    public function path_returns_provided_string_when_on_path()
    {
        $this->request->shouldReceive('is')->andReturn(true);

        $result = $this->active->path('foo', 'bar');

        $this->assertEquals('bar', $result, "Incorrect class is returned when path is matched.");
    }

    /** @test */
    public function path_returns_null_when_not_current_path()
    {
        $this->request->shouldReceive('is')->andReturn(false);

        $result = $this->active->path('foo');

        $this->assertNull($result, "Null is not returend when path is not matched.");
    }

    /** @test */
    public function route_returns_active_when_on_route()
    {
        $this->router->shouldReceive('is')->andReturn(true);

        $result = $this->active->route('foo');

        $this->assertEquals('active', $result, "Class is not returned when route is matched.");
    }

    /** @test */
    public function route_returns_provided_string_when_on_route()
    {
        $this->router->shouldReceive('is')->andReturn(true);

        $result = $this->active->route('foo', 'bar');

        $this->assertEquals('bar', $result, "Class is not returned when route is matched.");
    }

    /** @test */
    public function route_returns_null_when_not_current_route()
    {
        $this->router->shouldReceive('is')->andReturn(false);

        $result = $this->active->route('foo');

        $this->assertNull($result, "Null is not returned when route is not matched.");
    }
}
