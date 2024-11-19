<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetLfmFolder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengatur folder default untuk Laravel FileManager
       // Tentukan direktori folder berdasarkan jenis
       if ($request->has('folder')) {
       Log::info('Folder yang dipilih: ' . $request->get('folder'));
        session(['lfm_folder' => $request->get('folder')]);
    }

    return $next($request);

    }
}
