<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            abort(401, 'Unauthorized');
        }

        if ($request->user()->role === UserRole::Gurdian) {
            abort(403, '閲覧専用アカウントではこの操作を行えません。');
        }

        return $next($request);
    }
}
