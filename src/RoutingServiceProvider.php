<?php

namespace LaPress\Routing;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaPress\Support\ThemeBladeDirectory;

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
        Route::prefix(config('wordpress.url.backend_prefix'))
             ->namespace(static::NAMESPACE)
             ->group(__DIR__.'/Http/routes.php');

        return;
        Route::namespace('LaPress\Routing\Http\Controllers')->group(function () {
            $this->registerShowRoutes()
                 ->registerListRoutes();
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

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
