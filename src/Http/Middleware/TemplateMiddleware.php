<?php

namespace LaPress\Routing\Http\Middleware;

use Closure;
use LaPress\Routing\Http\BootstrapTrait;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class TemplateMiddleware
{
    use BootstrapTrait;

    /**
     * @param         $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $globals = $this->getGlobalsKeys();

        $this->bootstrap();

        $wordpressGlobals = $this->detectNewGlobals($globals);

        app()->instance('wordpress.globals', $wordpressGlobals);

        return $next($request);
    }

    public function bootstrap()
    {
        $this->runTemplateBootstrapScript();
    }
}