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
    protected $defer = true;

    /**
     * Bootstrap the sneaker's services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sneaker');

        $this->publishes([
            __DIR__.'/../config/sneaker.php' => config_path('sneaker.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \SquareBoat\Sneaker\Commands\Sneak::class,
            ]);
        }
    }

    /**
     * Register the sneaker's services.
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

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sneaker'];
    }
}
