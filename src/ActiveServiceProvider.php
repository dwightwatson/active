<?php

namespace Watson\Active;

use Illuminate\Support\ServiceProvider;

class ActiveServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Active::class, function () {
            return $this->app->make(Active::class);
        });

        $this->mergeConfigFrom(
            realpath(__DIR__ . '/../config/config.php'), 'active'
        );
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('active.php'),
        ], 'config');
    }
}
