<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AutenticacaoController extends Controller
{
    public function mostrarLogin(Request $request): View|RedirectResponse
    {
        if ($request->has('redefinir') || $request->has('reset')) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('components.usuario.login');
    }

    public function entrar(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $credenciais = [
            'email' => $request->email,
            'password' => $request->input('password', $request->input('senha')),
        ];

        if (!Auth::attempt($credenciais, $request->boolean('lembrar'))) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas estão incorretas.'],
            ]);
        }

        if (Auth::user()->status === 'inativo') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => ['Sua conta está desativada. Entre em contato com um administrador para reativação.'],
            ]);
        }

        // Regenera a sessão para prevenir ataques de Fixação de Sessão (Session Fixation)
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Login realizado com sucesso',
                'usuario' => Auth::user()->load('nivel'),
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function sair(Request $request): JsonResponse|RedirectResponse
    {
        // Desloga o usuário do guard web
        Auth::guard('web')->logout();

        // Invalida a sessão do PHP e regenera o token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['mensagem' => 'Logout realizado com sucesso']);
        }

        return redirect()->route('login');
    }

    public function usuarioAutenticado(Request $request): JsonResponse|View
    {
        $usuario = Auth::user()->load('nivel');

        if ($request->wantsJson()) {
            return response()->json($usuario);
        }

        return view('components.usuario.perfil', compact('usuario'));
    }

    public function atualizarPerfil(Request $request): JsonResponse|RedirectResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $dadosValidados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
            'cpf' => ['nullable', 'string', 'max:20', Rule::unique('usuarios', 'cpf')->ignore($usuario->id)],
            'telefone' => ['nullable', 'string', 'max:20'],
            'senha' => ['nullable', 'string', 'min:6'],
        ], [
            'nome.required' => 'O campo nome completo é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
            'senha.min' => 'A senha deve conter no mínimo 6 caracteres.',
        ]);

        if (!empty($dadosValidados['senha'])) {
            $dadosValidados['senha'] = Hash::make($dadosValidados['senha']);
        } else {
            unset($dadosValidados['senha']);
        }

        if (!Schema::hasColumn('usuarios', 'telefone')) {
            unset($dadosValidados['telefone']);
        }

        $usuario->update($dadosValidados);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Perfil atualizado com sucesso',
                'usuario' => $usuario->fresh()->load('nivel'),
            ]);
        }

        return redirect()->route('profile')->with('sucesso', 'Perfil atualizado com sucesso!');
    }
}