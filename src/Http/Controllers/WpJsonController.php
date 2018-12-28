<?php

namespace LaPress\Routing\Http\Controllers;


use LaPress\Routing\Http\Controllers\Admin\BaseController;

class WpJsonController extends BaseController
{
    public function show()
    {
        return $this->script()->run('index.php'); 
    }
}
