<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\AppConst;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!isset(auth()->user()->email_verified_at) || is_null(auth()->user()->email_verified_at))
        {
            return response()->json(["success" => false , "msg" => "Please verify user code"]);
        }
        
        if(auth()->user()->type != AppConst::EDITOR )
        {
            return response()->json(["success" => false , "msg" => "Unauthorized"]);
        }

        return $next($request);
    }
}
