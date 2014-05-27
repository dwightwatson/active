<?php

use Illuminate\Support\Facades\Route;

class ActiveHelpersTest extends PHPUnit_Framework_TestCase 
{
    public function testControllerNameGetsControllerName()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn('FooController@bar');

        $result = controller_name();

        $this->assertEquals('foo', $result, "Does not get correct controller name.");
    }

    public function testControllerNameGetsControllerWithNamespaces()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn('Baz\FooController@bar');

        $result = controller_name();

        $this->assertEquals('baz foo', $result, "Does not get the correct namespaced controller name,");
    }

    public function testControllerNameGetsControllerWithoutNamespaces()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn('Baz\FooController@bar');

        $result = controller_name(false);

        $this->assertEquals('foo', $result, "Does not get the correct namespaced controller name.");
    }

    public function testControllerNameWorksWhenNotOnController()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn(null);

        $result = controller_name();

        $this->assertNull($result, "Does not return null when not on controller route.");
    }


    public function testActionNameGetsActionName()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn('FooController@bar');

        $result = action_name();

        $this->assertEquals('bar', $result, "Does not get correct action name.");
    }

    public function testActionNameRemovesMethod()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn('FooController@getBar');

        $result = action_name();

        $this->assertEquals('bar', $result, "Does not remove the method from the action.");
    }

    public function testActionNameWorksWhenNotOnController()
    {
        Route::shouldReceive('currentRouteAction')->once()->andReturn(null);

        $result = action_name();

        $this->assertNull($result, "Does not return null when not on controller route.");
    }
}