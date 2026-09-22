<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $isLoginPage = $request->path() === '/';
        $isLoginRequest = $isLoginPage && $request->isMethod('POST');
        $isLogoutRequest = $request->path() === 'logout' && $request->isMethod('POST');

        if ($isLoginPage || $isLoginRequest || $isLogoutRequest) {
            return $next($request);
        }

        if (!session('logged_in')) {
            return redirect('/');
        }

        return $next($request);
    }
}