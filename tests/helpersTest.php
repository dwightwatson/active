<?php

use Illuminate\Support\Facades\App;
use PHPUnit\Framework\TestCase;
use Watson\Active\Active;
use Watson\Active\Route;

class HelpersTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function controller_name_calls_controller_method()
    {
        $routeMock = Mockery::mock(Route::class);

        $routeMock->shouldReceive('controller')->once()->with('foo', 'bar', 'baz')->andReturn('bat');

        App::shouldReceive('make')->once()->with(Route::class)->andReturn($routeMock);

        $result = controller_name('foo', 'bar', 'baz');

        $this->assertEquals('bat', $result);
    }

    /** @test */
    public function action_name_calls_action_method()
    {
        $routeMock = Mockery::mock(Route::class);

        $routeMock->shouldReceive('action')->once()->with('foo')->andReturn('bar');

        App::shouldReceive('make')->once()->with(Route::class)->andReturn($routeMock);

        $result = action_name('foo');

        $this->assertEquals('bar', $result);
    }

    /** @test */
    public function action_without_parameters_returns_instance()
    {
        $activeMock = Mockery::mock(Active::class);

        App::shouldReceive('make')->once()->with(Active::class)->andReturn($activeMock);

        $result = active();

        $this->assertEquals($activeMock, $result);
    }

    /** @test */
    public function active_calls_active_method()
    {
        $activeMock = Mockery::mock(Active::class);

        $activeMock->shouldReceive('active')->once()->with(['foo'], 'bar')->andReturn('baz');

        App::shouldReceive('make')->once()->with(Active::class)->andReturn($activeMock);

        $result = active('foo', 'bar');

        $this->assertEquals('baz', $result);
    }

    /** @test */
    public function is_active_calls_is_active_method()
    {
        $activeMock = Mockery::mock(Active::class);

        $activeMock->shouldReceive('isActive')->once()->with(['foo'])->andReturn('bar');

        App::shouldReceive('make')->once()->with(Active::class)->andReturn($activeMock);

        $result = is_active('foo');

        $this->assertEquals('bar', $result);
    }
}
