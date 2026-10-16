<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConviteController extends Controller
{
    public function mostrarConfirmacao(Request $request, int $id): View|RedirectResponse
    {
        $usuario = Usuario::with('nivel')->findOrFail($id);

        if ($usuario->status !== 'pendente') {
            if (Auth::check() && Auth::id() === $usuario->id) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('login')->with('info', 'Este convite já foi processado anteriormente. Por favor, faça login com suas credenciais.');
        }

        $remetente = $request->query('remetente');
        if (!$remetente) {
            $remetente = Usuario::where('role', 'administrador')->value('email') ?? 'admin@recantobetel.com.br';
        }

        return view('components.usuario.confirmar-convite', compact('usuario', 'remetente'));
    }

    public function aceitar(Request $request, int $id): RedirectResponse
    {
        $usuario = Usuario::findOrFail($id);

        // Se houver qualquer sessão ativa no navegador (ex: quem enviou o convite), desconecta imediatamente
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Ativa o usuário convidado
        $usuario->update([
            'status' => 'ativo',
        ]);

        // Autentica diretamente com a conta convidada
        Auth::guard('web')->login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Convite aceito com sucesso! Bem-vindo(a) ao Recanto Betel.');
    }

    public function recusar(Request $request, int $id): RedirectResponse
    {
        $usuario = Usuario::findOrFail($id);

        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $usuario->update([
            'status' => 'inativo',
        ]);

        return redirect()->route('login')->with('info', 'Convite recusado com sucesso.');
    }
}
