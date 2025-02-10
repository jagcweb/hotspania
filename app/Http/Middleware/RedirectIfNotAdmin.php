<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si la URL NO contiene "/admin/"
        if (!str_contains($request->path(), 'admin')) {
            return redirect()->route('admin.citychanges');
        }

        return $next($request);
    }
}
