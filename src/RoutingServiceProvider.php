<?php

namespace LaPress\Routing;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaPress\Routing\Http\Middleware\AdminMiddleware;
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
    }

    private function registerShowRoutes()
    {
        foreach (config('wordpress.posts.routes') as $postType => $data) {
            Route::get($data['route'], 'PostsController@show');
        }

        return $this;
    }

    private function registerListRoutes()
    {
        foreach (config('wordpress.posts.types') as $type => $model) {
            Route::get(str_plural($type), 'PostsController@index');
        }

        return $this;
    }
}
