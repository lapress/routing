<?php

namespace LaPress\Routing\Http;

use LaPress\Support\WordPress;

trait BootstrapTrait
{
    public function setVariables()
    {
        // set script name for 'wordpress/wp-includes/vars.php'
        // for NGINX
        if (isset($_SERVER['PATH_INFO'])) {
            $_SERVER['PHP_SELF'] = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['PHP_SELF'] = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * @return array
     */
    protected function getGlobalsKeys()
    {
        return array_keys($GLOBALS);
    }

    /**
     * @param array $globals_before_keys
     * @return array
     */
    protected function detectNewGlobals(array $globals_before_keys)
    {
        // retrieve & sort keys for $GLOBALS
        $globals_keys = array_keys($GLOBALS);
        sort($globals_keys);

        $new_globals = [];

        // enumerate keys
        foreach ($globals_keys as $key) {
            if (!in_array($key, $globals_before_keys)) {
                $new_globals[] = $key;
            }
        }

        return $new_globals;
    }

    protected function runTemplateBootstrapScript()
    {
        if (env('WP_MULTISITE', false)) {
            // for 'wp-includes/ms-functions.php'
            global $current_site;
            global $current_blog;

            // for 'wp-includes/ms-settings.php'
            global $blog_id;
            global $wpdb;
            global $_wp_switched_stack;
        } else {
            // no globals
        }

//        define('WP_CONTENT_URL', config('wordpress.url.site').'/wp-content');

        require_once wordpress_path('wp-load.php');

        $this->registerContentNamespaces();
    }


    /**
     *
     */
    protected function runAdminBootstrapScript()
    {
        if (env('WP_MULTISITE', false)) {
            // for 'wp-includes/ms-functions.php'
            global $current_site;
            global $current_blog;

            // for 'wp-includes/ms-settings.php'
            global $blog_id;
            global $wpdb;
            global $_wp_switched_stack;
        } else {
            // no globals
        }

//        define('WP_CONTENT_URL', config('wordpress.url.site').'/wp-content');

        require_once wordpress_path('wp-load.php');

        // for 'wp-admin/includes/file.php'
        global $wp_file_descriptions;

        require_once wordpress_path('wp-admin/includes/admin.php');

        // Add .blade.php description
        $file_descriptions = $wp_file_descriptions;
        foreach ($wp_file_descriptions as $filename => $description) {
            if (preg_match('/\.php$/', $filename)) {
                $file_descriptions[preg_replace('/\.php$/', '.blade.php', $filename)] = $description;
            }
        }
        $wp_file_descriptions = $file_descriptions;

        $this->registerContentNamespaces();
    }

    /**
     *
     */
    protected function registerContentNamespaces()
    {
        // Plugins
        foreach (WordPress::activePlugins() as $plugin_script) {

            if (!WordPress\Plugin::exists($plugin_script)) {
                info("Error: Plugin '$plugin_script' was not found.");
                continue;
            }

            $plugin_data = get_file_data(WordPress\Plugin::path($plugin_script), [
                'php_autoload_dir' => 'PHP Autoload',
                'php_namespace' => 'PHP Namespace',
            ]);

            if (array_get($plugin_data, 'php_autoload_dir')) {
                $plugin = preg_replace('/(\/.*$)|(.php$)/', '', $plugin_script);
                $plugin_path = wordpress_path('wp-content/plugins/'.$plugin);
                ContentClassLoader::addNamespace($plugin_path.'/'.$plugin_data['php_autoload_dir'], $plugin_data['php_namespace']);
            }
        }

        // Theme
        {
            $theme = WordPress::activetheme_path();
            $theme_path = WordPress::themePath($theme);


            if (!WordPress\Theme::exists($theme)) {
                info("Error: Theme '$theme' does not exist.");

                return;
            }
            
            $theme_data = get_file_data(WordPress\Theme::style($theme), [
                'php_autoload_dir' => 'PHP Autoload',
                'php_namespace' => 'PHP Namespace',
            ]);

            if (array_get($theme_data, 'php_autoload_dir')) {
                ContentClassLoader::addNamespace($theme_path.'/'.array_get($theme_data, 'php_autoload_dir', 'classes'), $theme_data['php_namespace']);
            }
        }
    }

    public function script()
    {
        return new ScriptRunner();
    }
}
