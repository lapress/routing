<?php

namespace LaPress\Routing\Http\Controllers\Admin;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class AdminController extends BaseController
{
    public function load($script)
    {
        return $this->script()
                    ->runAdminWithMenu($script.'.php');
    }

    /**
     * @return string
     */
    public function edit()
    {
        return $this->script()
                    ->runAdmin('admin.php');
    }

    /**
     * @return string
     */
    public function update()
    {
        return $this->script()
                    ->runAdmin('admin.php');
    }

    public function runPhpScript()
    {
        $path = app('request')->path();

        $prefix = config('wordpress.url.backend_prefix');

        // trim prefix
        if (starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
        }

        $path = wordpress_path($path);

        // ERROR: file not found
        if (!is_file($path)) {
            abort(404);
        }

        require $path;
    }

}