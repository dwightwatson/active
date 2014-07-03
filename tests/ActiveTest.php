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

    public function testPathWithCurrentPath()
    {
        $this->request->shouldReceive('is')->andReturn(true);

        $result = $this->active->path('foo');

        $this->assertEquals('active', $result, "Class is not returned when path is matched.");
    }

    public function testPathWithCurrentPathAndClass()
    {
        $this->request->shouldReceive('is')->andReturn(true);

        $result = $this->active->path('foo', 'bar');

        $this->assertEquals('bar', $result, "Incorrect class is returned when path is matched.");
    }

    public function testPathWithoutCurrentPath()
    {
        $this->request->shouldReceive('is')->andReturn(false);

        $result = $this->active->path('foo');

        $this->assertNull($result, "Null is not returend when path is not matched.");
    }

    public function testRouteWithCurrentRoute()
    {
        $this->router->shouldReceive('is')->andReturn(true);

        $result = $this->active->route('foo');

        $this->assertEquals('active', $result, "Class is not returned when route is matched.");
    }

    public function testRouteWithCurrentRouteAndClass()
    {
        $this->router->shouldReceive('is')->andReturn(true);

        $result = $this->active->route('foo', 'bar');

        $this->assertEquals('bar', $result, "Class is not returned when route is matched.");
    }

    public function testRouteWithoutCurrentRoute()
    {
        $this->router->shouldReceive('is')->andReturn(false);

        $result = $this->active->route('foo');

        $this->assertNull($result, "Null is not returned when route is not matched.");
    }


    public function testResourceWithCurrentRoute()
    {
        $this->router->shouldReceive('is')->andReturn(true);

        $this->router->shouldReceive('current')
            ->andReturn(Mockery::mock(['parameters' => 1]));

        $result = $this->active->resource('posts.show', 1);

        $this->assertEquals('active', $result, "Class is not returned when resource is matched.");
    }

    public function testResourceWithoutCurrentRoute()
    {
        $this->router->shouldReceive('is')->andReturn(true);

        $this->router->shouldReceive('current')
            ->andReturn(Mockery::mock(['parameters' => 2]));

        $result = $this->active->resource('posts.show', 1);

        $this->assertNull($result, "Null is not returned when resource is matched.");
    }

    public function testResourceWithoutNoRoute()
    {
        $this->router->shouldReceive('is')->andReturn(false);

        $result = $this->active->resource('posts.show', 1);

        $this->assertNull($result, "Null is not returned when resource is not matched.");
    }
}