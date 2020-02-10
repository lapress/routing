<?php

namespace LaPress\Routing\Http\Controllers\Admin;

use Parsedown;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class SetupController extends BaseController
{
    /**
     * @var Parsedown
     */
    private $parsedown;

    /**
     * @param Parsedown $parsedown
     */
    public function __construct(Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return $this->script()->runAdminWithMenu('setup-config.php');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function update()
    {
        return $this->script()->runAdminWithMenu('setup-config.php');
    }
}
