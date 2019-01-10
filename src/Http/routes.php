<?php

##########################
$prefix = 'wp-admin';
$adminUrl = config('wordpress.url.backend').'/'.$prefix.'/';
##########################

// Login Gate
// ?action = ['postpass', 'logout', logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register']
Route::get('wp-login.php', 'LoginController@create');
Route::post('wp-login.php', 'LoginController@store');

if (config('wordpress.url.backend') != config('wordpress.url.site')) {
    Route::redirect('', $adminUrl);
}

Route::get('login', 'LoginController@create');

Route::redirect('admin', $adminUrl);
Route::redirect('dashboard', $adminUrl);
Route::prefix($prefix)->group(function () {
    // 2. Dashboard
    Route::get('/', ['as' => 'wordpress.admin.dashboard', 'uses' => 'DashboardController@show']);
    Route::redirect('index.php', '/');

    // 3. Setup
    Route::get('setup-config.php', 'SetupController@edit');
    Route::post('setup-config.php', 'SetupController@update');

    Route::get('install.php', 'InstallController@edit');
    Route::post('install.php', 'InstallController@update');

    // 4. Admin
    Route::get('admin-ajax.php', 'AjaxAdminController@edit');
    Route::post('admin-ajax.php', 'AjaxAdminController@update');

    Route::get('load-styles.php', 'FilesController@loadStyles');
    Route::get('load-scripts.php', 'FilesController@loadScripts');

    Route::get('/maint/repair.php', 'MaintenanceController@show');
    Route::post('/maint/repair.php', 'MaintenanceController@show');

    Route::get('/{script}.php', 'AdminController@load');
    Route::post('/{script}.php', 'AdminController@load');

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

Route::prefix('wp-includes')->group(function () {
    Route::get('js/tinymce/wp-mce-help.php', 'AdminController@runPhpScript');
    Route::get('js/tinymce/wp-tinymce.php', 'AdminController@runPhpScript');

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
