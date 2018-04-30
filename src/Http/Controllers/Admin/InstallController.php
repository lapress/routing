<?php

namespace LaPress\Routing\Http\Controllers\Admin;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class InstallController extends BaseController
{
    public function __construct()
    {
    }
    /**
     * @return string
     */
    public function edit()
    {
        return $this->script()->runAdmin('install.php');
    }

    /**
     * @return string
     */
    public function update()
    {
        return $this->script()->runAdmin('install.php');
    }
}