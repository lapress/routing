<?php

namespace LaPress\Routing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $post = Post::withoutGlobalScopes()->findOneByName($slug);

        if (!$post || !in_array($post->post_type, config('wordpress.posts.routes.'.$post->post_type.'.post_types', []))) {
            throw new NotFoundHttpException();
        }

        if ($request->wantsJson()) {
            return $post;
        }

        return view()->first([
            'theme::'.$post->post_type,
            'theme::post',
        ], [
            'post' => $post,
        ]);
    }
}