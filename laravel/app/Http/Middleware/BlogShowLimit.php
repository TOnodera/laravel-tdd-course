<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlogShowLimit
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->runningUnitTests()) {
            return $next($request);
        }

        if (!\in_array($request->ip(), ['192.168.255.255'], true)) {
            abort(403, 'Your IP is not valid.');
        }

        return $next($request);
    }

    protected function runningUnitTests()
    {
        return app()->runningInConsole() && app()->runningUnitTests();
    }
}
