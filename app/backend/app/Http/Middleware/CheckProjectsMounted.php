<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProjectsMounted
{
    private const PLACEHOLDER = '/choose-your-projects-path';

    public function handle(Request $request, Closure $next)
    {
        $saidRoot = env('SAID_ROOT', '');

        if ($saidRoot === self::PLACEHOLDER || $saidRoot === '' || !is_dir('/said-projects') || !is_readable('/said-projects')) {
            return response()->view('install.restart', [
                'said_root'    => $saidRoot ?: self::PLACEHOLDER,
                'mounted'      => false,
                'post_install' => true,
            ]);
        }

        return $next($request);
    }
}
