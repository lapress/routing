<?php

namespace LaPress\Routing\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use LaPress\Events\RegisterSearchEvent;
use LaPress\Routing\Http\Resources\PostResourceResolver;
use Laravel\Scout\Builder;

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

        if ($query === '*' || in_array($request->ip(), config('scout.blockedIps', []))) {
            return [];
        }

        $postResource = (new PostResourceResolver(new $postModel))->resolve();

        $posts = $postModel::search($query);
        $data = [
            'posts' => [
                'count' => $posts->count(),
                'items' => $postResource::collection($this->decorateSearch($posts)->take($take)->get()),
            ],
        ];

        foreach ($postTypes as $postType => $model) {
            $postResource = (new PostResourceResolver(new $model))->resolve();
            $search = $model::search($query);
            $data[$postType] = [
                'count' => $search->count(),
                'items' => $postResource::collection($this->decorateSearch($search)->take($take)->get()),
            ];
        }

        RegisterSearchEvent::dispatch($query);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * @param Builder $posts
     * @return Builder
     */
    protected function decorateSearch(Builder $posts)
    {
        return $posts;
    }
}
