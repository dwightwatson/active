<?php namespace Watson\Active;

use Illuminate\Support\ServiceProvider;

class ActiveServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->package('watson/active');

		$this->app->bind('active', function()
		{
			return new \Watson\Active\Active($app['request'], $app['router']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('active');
	}

}