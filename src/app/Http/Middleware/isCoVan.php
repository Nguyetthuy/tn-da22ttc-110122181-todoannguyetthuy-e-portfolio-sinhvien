<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isCoVan
{
    public function handle(Request $request, Closure $next)
    {
        $permission = $request->session()->get('user_permission');
        // Cho phép giảng viên (2) hoặc tài khoản cố vấn riêng (6)
        if ($request->session()->has('user_permission') && in_array($permission, [2, 6]))
            return $next($request);
        else
            return redirect('/dang-xuat');
    }
}