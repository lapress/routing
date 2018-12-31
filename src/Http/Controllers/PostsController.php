<?php

namespace LaPress\Routing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaPress\Models\AbstractPost;
use LaPress\Models\DataProviders\PostMetaData;
use LaPress\Routing\Http\Resources\PostResourceResolver;
use LaPress\Support\WordPress\PostModelResolver;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostsController extends Controller
{
    /**
     * @param string  $slug
     * @param Request $request
     * @return Response
     */
    public function show(string $slug, Request $request)
    {
        $class = app(PostModelResolver::class)->resolve();
        $post = $class::withoutGlobalScopes()->findOneByName($slug);
        
        abort_unless($this->allow($post), 404);

        if ($request->wantsJson()) {
            return (new PostResourceResolver($post))->resolve();
        }

        PostMetaData::provide($post);

        return view()->first([
            'theme::'.$post->post_type,
            'theme::post',
        ], [
            'post' => $post,
        ]);
    }

    /**
     * @param object|AbstractPost $post
     * @return bool
     */
    private function allow($post)
    {
        if (!$post instanceof AbstractPost) {
            return false;
        }

        return in_array(
            $post->post_type,
            config('wordpress.posts.routes.'.$post->post_type.'.post_types', []) //
        );
    }
}
