<?php

namespace App\Http\Middleware;

use Closure;

class FilterIfPjax
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->pjax()) {
            $request->attributes->set('layout', 'admin.content');
        } else {
            $request->attributes->set('layout', 'admin.index');
        }

        return $next($request);
    }
}
