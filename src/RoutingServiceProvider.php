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
    const NAMESPACE = 'LaPress\Routing\Http\Controllers\Admin';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
            Router::showRoutes();

            Route::prefix(config('wordpress.url.backend_prefix'))
             ->namespace(static::NAMESPACE)
             ->group(__DIR__.'/Http/routes.php');
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
        $this->app['router']->aliasMiddleware('postType', PostTypeMiddleware::class);
    }
}
