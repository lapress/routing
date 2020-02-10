<?php

namespace LaPress\Routing\Http\Controllers\Admin;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class MaintenanceController extends BaseController
{
    public function __construct()
    {
    }
    /**
     * @return string
     */
    public function show()
    {
        return $this->script()->runAdmin('maint/repair.php');
    }
}
