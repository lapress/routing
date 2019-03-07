<?php

namespace LaPress\Routing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use LaPress\Models\AbstractPost;
use LaPress\Models\DataProviders\PostListMetaData;
use LaPress\Models\DataProviders\PostMetaData;
use App\Models\Post;
use LaPress\Routing\Http\Resources\PostResourceResolver;
use LaPress\Support\WordPress\PostModelResolver;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostsController extends Controller
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
     * @param string  $slug
     * @param Request $request
     * @return Response
     */
    public function show(string $slug, Request $request)
    {
        $class = $this->postModelResolver->resolve();

        $post = $class::withoutGlobalScopes()->findOneByName($slug);

        abort_unless($this->allow($post), 404);

        if ($request->wantsJson()) {
            return (new PostResourceResolver($post))->resolve();
        }

        PostMetaData::provide($post);

        $data = $this->beforeShow($post);

        return view()->first([
            theme_view($post->getPostTypePlural().'.show'),
            theme_view($post->post_type),
            theme_view('post'),
        ], array_merge([
            'post' => $post,
        ], $data));
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, int $page = null)
    {
        /** @var AbstractPost $class */
        $class = $this->postModelResolver->resolve();
        $page = $page ?: $request->page ?: 1;
        $take = config('wordpress.posts.per_page', 15);
        $posts = $class::recent()->paginate($take);
        $type = $this->postModelResolver->getPostType();
        $typePlural = str_plural($type);
        $page = Page::findOneByName($typePlural);

        PostListMetaData::provide($typePlural, $page);

        $data = $this->beforeIndex($posts);

        if ($request->wantsJson()) {
            $postResource = (new PostResourceResolver(new $class))->resolve();

            return $postResource::collection($posts);
        }

        return view()->first([
            theme_view($typePlural.'.index'),
            theme_view('posts'),
            theme_view('index'),
        ], array_merge([
            'posts'      => $posts,
            'type'       => $type,
            'typePlural' => $typePlural,
        ], $data));
    }

    /**
     * @param Post $post
     * @return array
     */
    protected function beforeShow(Post $post): array
    {
       return [];
    }


    /**
     * @param Collection|LengthAwarePaginator $posts
     * @return array
     */
    protected function beforeIndex($posts): array
    {
        return [];
    }

    /**
     * @param object|AbstractPost $post
     * @return bool
     */
    protected function allow($post)
    {
        if (!$post instanceof AbstractPost) {
            return false;
        }
        $type = $post->post_type;

        if ($type === 'page' && empty(config('wordpress.posts.routes.page'))) {
            $type = 'post';
        }

        return $post->isPublished() && in_array(
                $post->post_type,
                config('wordpress.posts.routes.'.$type.'.post_types', []) //
            );
    }
}
