<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();

        if (!$usuario || $usuario->role !== 'administrador') {
            if ($request->wantsJson()) {
                return response()->json([
                    'mensagem' => 'Você não possui permissão para gerenciar o painel. Solicite acesso a um administrador.',
                ], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Você não possui permissão para acessar o painel administrativo.');
        }

        return $next($request);
    }
}