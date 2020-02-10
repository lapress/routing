<?php

namespace LaPress\Routing\Http;

use LaPress\Support\WordPress;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ScriptRunner
{
    public function bootstrap()
    {

    }

    /**
     * @param       $filename
     * @param array $globals
     * @return string
     */
    public function runAdminWithMenu($filename, array $globals = [])
    {
        // 'wp-admin/menu.php', 'wp-admin/includes/menu.php'
        $globals = array_merge($globals, ['menu', 'submenu', '_wp_menu_nopriv', '_wp_submenu_nopriv']);

        // for sort_menu() in wp-admin/includes/menu.php
        $globals = array_merge($globals, ['menu_order', 'default_menu_order']);

        // for wp-admin/includes/plugin.php
        $globals = array_merge($globals, ['_wp_last_object_menu', '_wp_last_utility_menu']);

        return $this->runAdmin($filename, $globals);
    }

    /**
     * @param       $filename
     * @param array $globals
     * @return string
     */
    public function runAdmin($filename, array $globals = [])
    {
        // from wp-settings.php
        $globals = array_merge($globals, ['wp_version', 'wp_db_version', 'tinymce_version', 'required_php_version', 'required_mysql_version']);

        // require current directory is '{$WORDPRESS}/wp-admin/' by 'wp-admin/menu-header.php'
        chdir(wordpress_path('wp-admin'));

        return $this->run('wp-admin/'.$filename, $globals);
    }

    public function run($path, array $globals = [])
    {
        // add script specified global variables
        $globals = array_merge($globals, WordPress::globals($path) ?: []);

        foreach ($globals as $global) {
            global ${$global};
        }

        ob_start();

        if (env('APP_ENV') == 'testing1') {


            // We'll evaluate the contents of the view inside a try/catch block so we can
            // flush out any stray output that might get out before an error occurs or
            // an exception is thrown. This prevents any partial views from leaking.
            try {
                require wordpress_path($path);
            } catch (Exception $e) {
                ob_end_clean();
            }

            return ltrim(ob_get_clean());
        } else {
            require wordpress_path($path);
            $response = ob_get_contents();
            ob_end_clean();
            return $response;
        }
    }
}
