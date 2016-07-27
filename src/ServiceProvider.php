<?php

namespace Minhbang\Ebook;

use Illuminate\Routing\Router;
use Minhbang\Kit\Extensions\BaseServiceProvider;
use Minhbang\Enum\Enum;
use CategoryManager;
use MenuManager;
use AccessControl;

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
                __DIR__ . '/../views'            => base_path('resources/views/vendor/ebook'),
                __DIR__ . '/../lang'             => base_path('resources/lang/vendor/ebook'),
                __DIR__ . '/../config/ebook.php' => config_path('ebook.php'),
            ]
        );
        $this->publishes(
            [
                __DIR__ . '/../database/migrations/2015_11_30_000000_create_ebooks_table.php' =>
                    database_path('migrations/2015_11_30_000000_create_ebooks_table.php'),
            ],
            'db'
        );

        $this->mapWebRoutes($router, __DIR__ . '/routes.php', config('ebook.add_route'));

        $class = Ebook::class;
        // pattern filters
        $router->pattern('ebook', '[0-9]+');
        // model bindings
        $router->model('ebook', $class);
        Enum::registerResources([$class]);
        CategoryManager::register($class, config('ebook.category'));
        MenuManager::addItems(config('ebook.menus'));
        AccessControl::register($class, config('ebook.access_control'));
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
