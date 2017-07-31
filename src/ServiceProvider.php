<?php namespace Minhbang\Ebook;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MenuManager;
use Kit;
use CategoryManager;
use Authority;
use Status;
use Enum;


/**
 * Class ServiceProvider
 *
 * @package Minhbang\Ebook
 */
class ServiceProvider extends BaseServiceProvider {
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot( Router $router ) {
        $this->loadTranslationsFrom( __DIR__ . '/../lang', 'ebook' );
        $this->loadViewsFrom( __DIR__ . '/../views', 'ebook' );
        $this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
        $this->loadRoutesFrom( __DIR__ . '/routes.php' );
        $this->publishes(
            [
                __DIR__ . '/../views'            => base_path( 'resources/views/vendor/ebook' ),
                __DIR__ . '/../lang'             => base_path( 'resources/lang/vendor/ebook' ),
                __DIR__ . '/../config/ebook.php' => config_path( 'ebook.php' ),
            ]
        );

        $class = Ebook::class;
        // pattern filters
        $router->pattern( 'ebook', '[0-9]+' );
        // model bindings
        $router->model( 'ebook', $class );

        Kit::alias( $class, 'ebook' );
        Kit::title( $class, trans( 'ebook::common.ebook' ) );

        Kit::writeablePath( 'my_upload:' . config( 'ebook.featured_image.dir' ), 'trans::ebook::common.featured_image_dir' );
        CategoryManager::register( $class );
        MenuManager::addItems( config( 'ebook.menus' ) );
        Status::register( $class, config( 'ebook.status_manager' ) );
        Authority::permission()->registerCRUD( $class );
        Enum::register( $class, [
            'language'  => [ 'title' => 'trans::ebook::common.language_id', 'attr' => 'language_id' ],
            'security'  => [ 'title' => 'trans::ebook::common.security_id', 'attr' => 'security_id' ],
            'writer'    => [ 'title' => 'trans::ebook::common.writer_id', 'attr' => 'writer_id' ],
            'publisher' => [ 'title' => 'trans::ebook::common.publisher_id', 'attr' => 'publisher_id' ],
            'pplace'    => [ 'title' => 'trans::ebook::common.pplace_id', 'attr' => 'pplace_id' ],
        ] );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom( __DIR__ . '/../config/ebook.php', 'ebook' );
    }
}
