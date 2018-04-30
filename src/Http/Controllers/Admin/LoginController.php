<?php

namespace LaPress\Routing\Http\Controllers\Admin;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class LoginController extends BaseController
{
    public function __construct()
    {
    }
    /**
     * @return string
     */
    public function create()
    {
        return $this->script()->run('wp-login.php', [
            'wpdb',
            'current_site', // for wp-includes/ms-functions.php
            'user_login', 'error',
        ]);
    }

    /**
     * @return string
     */
    public function store()
    {
        return $this->script()->run('wp-login.php', [
            'wpdb',
            'current_site', // for wp-includes/ms-functions.php
            'user_login', 'error',
        ]);
    }
}