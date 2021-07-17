<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class checkActivationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('id',auth()->user()->id)->where('registered_at','!=',null)->first();

        if ($user == null) {
            return response(['error' => 'User is not activated.'],403);
        }

        return $next($request);
    }
}
