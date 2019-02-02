<?php

namespace LaPress\Routing\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use LaPress\Events\RegisterSearchEvent;
use LaPress\Routing\Http\Resources\PostResourceResolver;
use LaPress\Support\WordPress\PostModelResolver;

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
        $take = $request->take ?: 100;

        RegisterSearchEvent::dispatch($query, $class);

        $resource = (new PostResourceResolver(new $class))->resolve();

        return $resource::collection(
            $class::search($query)->take($take)->get()
        );
    }
}
