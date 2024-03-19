<?php

namespace SquareBoat\Sneaker;

use Illuminate\Support\ServiceProvider;
use SquareBoat\Sneaker\Commands\Sneak;

class SneakerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sneaker');

        $this->publishes([
            __DIR__ . '/../resources/views/email' => resource_path('views/vendor/sneaker/email')
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/sneaker.php' => config_path('sneaker.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Sneak::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/sneaker.php', 'sneaker'
        );

        $this->app->singleton('sneaker', function () {
            return $this->app->make(Sneaker::class);
        });
    }
}
