<?php

namespace Minhbang\Ebook;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\Ebook
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'ebook');
        $this->loadViewsFrom(__DIR__ . '/../views', 'ebook');
        $this->publishes(
            [
                __DIR__ . '/../views'                      => base_path('resources/views/vendor/ebook'),
                __DIR__ . '/../lang'                       => base_path('resources/lang/vendor/ebook'),
                __DIR__ . '/../config/ebook.php'            => config_path('ebook.php'),
                __DIR__ . '/../database/migrations/' .
                '2015_11_28_000000_create_ebooks_table.php' =>
                    database_path('migrations/' . '2015_11_28_000000_create_ebooks_table.php'),
            ]
        );

        if (config('ebook.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
        // pattern filters
        $router->pattern('ebook', '[0-9]+');
        // model bindings
        $router->model('ebook', 'Minhbang\Ebook\Ebook');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ebook.php', 'ebook');
    }
}
