<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminOrEditor
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'editor'])) {
            return $next($request);
        }
    
        abort(403, 'Niste autorizovani da pristupite ovoj stranici.');
    }
    
}
