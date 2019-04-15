<?php

namespace LaPress\Routing\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use LaPress\Events\RegisterSearchEvent;
use LaPress\Routing\Http\Resources\PostResourceResolver;
use LaPress\Support\WordPress\PostModelResolver;
use Laravel\Scout\Builder;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class SearchPostsController extends BaseController
{
    /**
     * @var PostModelResolver
     */
    protected $postModelResolver;

    /**
     * @param PostModelResolver $postModelResolver
     */
    public function __construct(PostModelResolver $postModelResolver)
    {
        $this->postModelResolver = $postModelResolver;
    }

    /**
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {
        /** @var AbstractPost $class */
        $class = $this->postModelResolver->resolve();
        $query = $request->q;
        $take = $request->take ?: 40;

        if ($query === '*' || in_array($request->ip(), config('scout.blockedIps', []))) {
            return [];
        }

        RegisterSearchEvent::dispatch($query, $class);

        $resource = (new PostResourceResolver(new $class))->resolve();

        return $resource::collection(
            $this->decorateSearch($class::search($query))->paginate($take)
        );
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
