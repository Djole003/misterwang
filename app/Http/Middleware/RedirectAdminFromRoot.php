<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectAdminFromRoot
{
    public function handle(Request $request, Closure $next)
    {
        // Ako je korisnik ulogovan I admin je
        if (
            auth()->check() &&
            auth()->user()->role === 'admin'
        ) {
            // admin NE SME na /
            return redirect()->route('index'); // /pocetna
        }

        // svi ostali (gost + editor) prolaze normalno
        return $next($request);
    }
}
