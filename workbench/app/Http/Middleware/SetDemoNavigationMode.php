<?php

declare(strict_types=1);

namespace Workbench\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetDemoNavigationMode
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('filter')) {
            $request->session()->put('demo_filter_navigation', $request->boolean('filter'));
        }

        if ($request->has('sticky')) {
            $request->session()->put('demo_sticky_search', $request->boolean('sticky'));
        }

        return $next($request);
    }
}
