<?php

namespace LaPress\Routing\Http;

use Route;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Router
{
    public static function listRoutes()
    {
        Route::namespace('LaPress\Routing\Http\Controllers')->group(function () {
            foreach (config('wordpress.posts.types') as $type => $model) {
                Route::get(str_plural($type), 'PostsController@index');
            }
            Route::get(config('wordpress.posts.route'), 'PostsController@index');
        });
    }

    public static function showRoutes()
    {
        Route::namespace('LaPress\Routing\Http\Controllers')->group(function () {

            Route::get('wp-json/wp/v2/{f1?}/{f2?}/{f3?}/{f4?}/{f5?}/{f6?}/{f7?}/{f8?}/{f9?}', 'WpJsonController@show');
            Route::post('wp-json/wp/v2/{f1?}/{f2?}/{f3?}/{f4?}/{f5?}/{f6?}/{f7?}/{f8?}/{f9?}', 'WpJsonController@show');

            Route::middleware('cache.response')->group(function () {
                foreach (config('wordpress.posts.routes', []) as $postType => $data) {
                    Route::get($data['route'], 'PostsController@show');
                }
            });

            Route::prefix('wp-content')->group(function () {
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
        });
    }
}
