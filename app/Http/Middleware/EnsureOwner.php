<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isOwner()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak. Fitur ini hanya dapat diakses oleh Pemilik (Owner / Admin).'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Fitur ini khusus untuk Pemilik (Owner / Admin). Kasir hanya berwenang menginput pesanan dan melayani pelanggan.');
        }

        return $next($request);
    }
}
