<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InstallMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! env("APP_INSTALLED", false) && ! $request->is("install*")) {
            return redirect()->to("/install");
        }

        return $next($request);
    }
}
