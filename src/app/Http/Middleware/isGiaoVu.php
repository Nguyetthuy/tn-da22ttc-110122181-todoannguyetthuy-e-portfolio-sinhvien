<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isGiaoVu
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
        if($request->session()->has('user_permission') && $request->session()->get('user_permission') ==3)
            return $next($request);
        else
            return redirect('/dang-xuat');
    }
}
