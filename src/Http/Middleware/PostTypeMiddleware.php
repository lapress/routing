<?php

namespace LaPress\Routing\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostTypeMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
