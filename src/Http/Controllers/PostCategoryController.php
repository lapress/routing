<?php

namespace LaPress\Routing\Http\Controllers;

use Illuminate\Http\Request;
use LaPress\Models\Category;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostCategoryController
{
    /**
     * @param string  $slug
     * @param Request $request
     * @return mixed
     */
    public function show(string $slug, Request $request)
    {
        $category = Category::getByName($slug);

        return view()->first([
            'theme::category',
            'theme::index'
        ],[
            'category' => $category
        ]);
    }
}