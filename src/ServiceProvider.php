<?php

namespace Minhbang\Ebook;

use Illuminate\Routing\Router;
use Minhbang\Kit\Extensions\BaseServiceProvider;
use Minhbang\Enum\Enum;
use CategoryManager;
use Status;
use MenuManager;

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

        // pattern filters
        $router->pattern('ebook', '[0-9]+');
        // model bindings
        $router->model('ebook', 'Minhbang\Ebook\Ebook');

        Enum::registerResources([Ebook::class]);
        Status::register(Ebook::class, config('ebook.status_manager'));
        CategoryManager::register(Ebook::class, trans('ebook::common.ebook'), config('ebook.category_max_depth'));
        // Add ebook menus
        MenuManager::addItems(config('ebook.menus'));
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
