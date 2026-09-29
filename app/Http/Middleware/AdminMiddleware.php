<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user()){
            abort(401, 'Unauthorized'); 
        }

        if($request->user()->role !== UserRole::Admin){
            abort(403, 'Forbidden'); 
        }
        
        return $next($request);
    }
}
