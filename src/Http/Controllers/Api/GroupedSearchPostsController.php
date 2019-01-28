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

        $data = [
            'posts' => $postModel::search($query)->take($take)->get()
        ];

        foreach ($postTypes as $postType => $model) {
            $data[$postType] = $model::search($query)->take($take)->get();
        }

        return response()->json($data, Response::HTTP_OK);
    }
}
