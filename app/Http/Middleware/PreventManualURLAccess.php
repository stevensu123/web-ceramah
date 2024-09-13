<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventManualURLAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Contoh logika untuk memeriksa akses
        $previousUrl = url()->previous();

        // Periksa apakah URL sebelumnya mengandung '/cerita/view/create'
        if (!str_contains($previousUrl, '/cerita/view/create')) {
            // Redirect ke halaman yang benar jika tidak mengakses melalui rute yang sesuai
            return redirect('/cerita/view/create/' . $request->route('date'));
        }

        return $next($request);
    }
}
