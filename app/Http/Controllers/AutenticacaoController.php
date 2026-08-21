<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AutenticacaoController extends Controller
{
    public function entrar(LoginRequest $request): JsonResponse
    {
        // Tenta autenticar mapeando 'senha' para a coluna configurada no Model
        $credenciais = [
            'email' => $request->email,
            'password' => $request->senha, // O Laravel mapeia 'password' interno para o getAuthPasswordName()
        ];

        if (!Auth::attempt($credenciais, $request->boolean('lembrar'))) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas estão incorretas.'],
            ]);
        }

        // Regenera a sessão para prevenir ataques de Fixação de Sessão (Session Fixation)
        $request->session()->regenerate();

        return response()->json([
            'mensagem' => 'Login realizado com sucesso',
            'usuario' => Auth::user()->load('nivel'),
        ]);
    }

    public function sair(Request $request): JsonResponse
    {
        // Desloga o usuário do guard web
        Auth::guard('web')->logout();

        // Invalida a sessão do PHP e regenera o token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['mensagem' => 'Logout realizado com sucesso']);
    }

    public function usuarioAutenticado(): JsonResponse
    {
        return response()->json(Auth::user()->load('nivel'));
    }
}