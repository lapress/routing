<?php

namespace LaPress\Routing\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostsController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector|string
     */
    public function index(Request $request)
    {
        if ($request->get('post_type') == 'Array') {
            $url = str_replace('&post_type=Array', '', app('request')->fullUrl());

            return redirect($url);
        }

        return $this->script()->runAdminWithMenu('edit.php');
    }

    /**
     * @return string
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function update()
    {
        return $this->script()->runAdminWithMenu('edit.php');
    }

    public function create()
    {
        return $this->script()->runAdminWithMenu('post-new.php');
    }

    public function store()
    {
        return $this->script()->runAdminWithMenu('post-new.php');
    }
}