<?php
namespace LaPress\Routing\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
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
    private $postModelResolver;

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

        return $class::search($request->q)->paginate();
    }
}
