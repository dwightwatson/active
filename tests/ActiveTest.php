<?php

class ActiveTest extends PHPUnit_Framework_TestCase 
{
    protected $active;

    protected $request;

    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock('Illuminate\Http\Request');

        $this->router = Mockery::mock('Illuminate\Routing\Router');

        $this->active = new Watson\Active\Active($this->request, $this->router);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testIsWithCurrentRoute()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(true);

        $result = $this->active->is('foo');

        $this->assertTrue($result, "Not true when provided with the current route.");
    }

    public function testIsWithoutCurrentRoute()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(false);

        $result = $this->active->is('foo');

        $this->assertFalse($result, "True when not provided with the current route.");
    }

    public function testIsWithArrayIncludingCurrentRoute()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(false);
        $this->request->shouldReceive('is')->with('bar')->andReturn(true);

        $result = $this->active->is(array('foo', 'bar'));

        $this->assertTrue($result, "Not true when provided with array including the current route.");
    }

    public function testIsWithArrayWithoutCurrentRoute()
    {
        $this->request->shouldReceive('is')->andReturn(false);

        $result = $this->active->is(array('foo', 'bar'));

        $this->assertFalse($result, "True when not provided with the current route in an array.");
    }

    public function testIsWithCurrentRouteExcluded()
    {
        $this->request->shouldReceive('is')->with('foo')->andReturn(true);

        $result = $this->active->is('not:foo');

        $this->assertFalse($result, "True when current route is to be excluded.");
    }


    public function testPathWithCurrentRoute()
    {
        $this->request->shouldReceive('is')->andReturn(true);

        $result = $this->active->path('foo');

        $this->assertEquals('active', $result, "Class is not returned when path is matched.");
    }

    public function testPathWithCurrentRouteAndClass()
    {
        $this->request->shouldReceive('is')->andReturn(true);

        $result = $this->active->path('foo', 'bar');

        $this->assertEquals('bar', $result, "Incorrect class is returned when path is matched.");
    }

    public function testPathWithoutCurrentRoute()
    {
        $this->request->shouldReceive('is')->andReturn(false);

        $result = $this->active->path('foo');

        $this->assertNull($result, "Null is not returend when path is not matched.");
    }

    public function testRouteWhenNotOnANamedRoute()
    {
        $this->router->shouldReceive('current')->andReturn(false);

        $result = $this->active->route('foo');

        $this->assertNull($result, "Null is not returned when not on a named route.");
    }

    public function testRouteWithCurrentRoute()
    {
        $route = Mockery::mock('Illuminate\Routing\Route')->shouldReceive('getName')->andReturn('foo')->getMock();
        $this->router->shouldReceive('current')->andReturn($route);

        $result = $this->active->route('foo');

        $this->assertEquals('active', $result, "Class is not returned when route is matched.");
    }

    public function testRouteWithCurrentRouteAndClass()
    {
        $route = Mockery::mock('Illuminate\Routing\Route')->shouldReceive('getName')->andReturn('foo')->getMock();
        $this->router->shouldReceive('current')->andReturn($route);

        $result = $this->active->route('foo', 'bar');

        $this->assertEquals('bar', $result, "Class is not returned when route is matched.");
    }

    public function testRouteWithoutCurrentRoute()
    {
        $route = Mockery::mock('Illuminate\Routing\Route')->shouldReceive('getName')->andReturn('bar')->getMock();
        $this->router->shouldReceive('current')->andReturn($route);

        $result = $this->active->route('foo');

        $this->assertNull($result, "Nullis not returned when route is not matched.");
    }
}