<?php

namespace LaPress\Routing\Http;

use Illuminate\Support\Facades\Route;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Router
{
    const NAMESPACE = 'LaPress\Routing\Http\Controllers';
    const BACKEND_NAMESPACE = 'LaPress\Routing\Http\Controllers\Admin';

    /**
     * @param bool $withDefaultList
     */
    public static function lists($withDefaultList = true)
    {
        foreach (config('wordpress.posts.types') as $type => $model) {
            Route::get(str_plural($type), 'PostsController@index');
        }

        if (!$withDefaultList) {
            return;
        }
        Route::get(config('wordpress.posts.route'), 'PostsController@index');
    }

    public static function show()
    {
        Route::get('wp-json/wp/v2/{f1?}/{f2?}/{f3?}/{f4?}/{f5?}/{f6?}/{f7?}/{f8?}/{f9?}', 'WpJsonController@show');
        Route::post('wp-json/wp/v2/{f1?}/{f2?}/{f3?}/{f4?}/{f5?}/{f6?}/{f7?}/{f8?}/{f9?}', 'WpJsonController@show');

        Route::middleware('cache.response')->group(function () {
            foreach (config('wordpress.posts.routes', []) as $postType => $data) {
                Route::get($data['route'], 'PostsController@show');
            }
        });

        Route::prefix(str_after(config('wordpress.content.url'), '/'))->group(function () {
            Route::get('{f1}', 'FilesController@stream');
            Route::get('{f1}/{f2}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}/{f4}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}/{f4}/{f5}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}/{f8}', 'FilesController@stream');
            Route::get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}/{f8}/{f9}', 'FilesController@stream');
        });
    }

    /**
     *
     */
    public static function backend()
    {
        Route::prefix(config('wordpress.url.backend_prefix'))
             ->namespace(static::BACKEND_NAMESPACE)
             ->group(__DIR__.'/routes.php');
    }

    /**
     * @param bool $postTypes
     */
    public static function search($postTypes = true)
    {
        Route::prefix('search')->group(function () use ($postTypes) {
            if ($postTypes) {
                foreach (config('wordpress.posts.types') as $type => $model) {
                    Route::get(str_plural($type), 'SearchPostsController@index');
                }
            }

            Route::get('/', 'SearchPostsController@index');
        });
    }
}
