<?php

namespace LaPress\Routing\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class GroupedSearchPostsController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->q;
        $take = $request->take ?: config('wordpress.posts.search.grouped.take', 5);
        $postModel = config('wordpress.posts.model', Post::class);
        $postTypes = config('wordpress.posts.search.searchable', []);

        $posts = $postModel::search($query);
        $data = [
            'posts' => [
                'count' => $posts->count(),
                'items' => $posts->take($take)->get(),
            ],
        ];

        foreach ($postTypes as $postType => $model) {
            $search = $model::search($query);
            $data[$postType] = [
                'count' => $search->count(),
                'items' => $search->take($take)->get(),
            ];
        }

        return response()->json($data, Response::HTTP_OK);
    }
}
