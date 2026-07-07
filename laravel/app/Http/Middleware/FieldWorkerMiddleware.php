<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FieldWorkerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->isFieldWorker() || Auth::user()->isAdmin())) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses petugas lapangan.');
    }
}
