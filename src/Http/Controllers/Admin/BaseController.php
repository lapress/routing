<?php

namespace LaPress\Routing\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use LaPress\Routing\Http\BootstrapTrait;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class BaseController extends Controller
{
    use BootstrapTrait;

    public function __construct()
    {
        $this->middleware('wp-admin');
    }
}