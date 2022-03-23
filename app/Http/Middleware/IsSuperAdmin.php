<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;

class IsSuperAdmin
{
    use RespondsWithHttpStatus;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role->name == 'Super Admin') {

            return $next($request);
        }
        
        return $this->failure('you are not permitted to perform this action', Response::HTTP_UNAUTHORIZED);
    }
}
