<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Silahkan login terlebih dahulu!');
        }

        $user = Auth::user();

        // Cek apakah role user ada dalam daftar roles yang diperbolehkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action. Hanya ' . implode(', ', $roles) . ' yang dapat mengakses halaman ini.');
    }
}
