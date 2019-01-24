<?php

namespace LaPress\Routing;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaPress\Routing\Http\Middleware\AdminMiddleware;
use LaPress\Routing\Http\Middleware\PostTypeMiddleware;
use LaPress\Routing\Http\Router;
use Spatie\ResponseCache\Middlewares\CacheResponse;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Router::backend();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('wp-admin', AdminMiddleware::class);
        $this->app['router']->aliasMiddleware('cache.response', CacheResponse::class);

        Route::macro('postTypes', function ($withDefaultList = true) {
            Router::show();
            Router::lists();
        });

        Route::macro('postSearch', function ($postTypes = true){
            Router::search($postTypes);
        });
    }
}
