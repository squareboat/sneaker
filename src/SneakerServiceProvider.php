<?php

namespace SquareBoat\Sneaker;

use Illuminate\Support\ServiceProvider;

class SneakerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sneaker');

        $this->publishes([
            __DIR__ . '/../resources/views/email' => resource_path('views/vendor/sneaker/email')
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/sneaker.php' => config_path('sneaker.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/sneaker.php', 'sneaker'
        );

        $this->app->singleton('sneaker', function () {
            return $this->app->make(Sneaker::class);
        });
    }
}
