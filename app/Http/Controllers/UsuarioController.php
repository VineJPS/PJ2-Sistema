<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtualizarUsuarioRequest;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function __construct(
        protected UsuarioService $usuarioService
    ) {}

    public function listar(Request $request): JsonResponse|View
    {
        $search = $request->query('search');
        $tab = $request->query('tab', 'todos');

        $usuarios = $this->usuarioService->listarPaginado($search, $tab, 10);

        if ($request->wantsJson()) {
            return response()->json($usuarios);
        }

        return view('components.admin.index', compact('usuarios', 'search', 'tab'));
    }

    public function buscarPorId(int $id): JsonResponse
    {
        return response()->json($this->usuarioService->buscarPorId($id));
    }

    public function salvar(Request $request): JsonResponse|RedirectResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'funcao' => ['nullable', 'string', 'in:colaborador,administrador'],
            'role' => ['nullable', 'string', 'in:colaborador,administrador'],
            'nome' => ['nullable', 'string', 'max:255'],
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado no sistema.',
        ]);

        $role = $request->input('funcao', $request->input('role', 'colaborador'));
        $nome = $request->input('nome');

        $resultado = $this->usuarioService->convidar($dados['email'], $role, $nome);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Convite enviado com sucesso',
                'usuario' => $resultado['usuario'],
            ], 201);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Convite enviado com sucesso para ' . $resultado['usuario']->email . '!');
    }

    public function tornarAdmin(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $usuario = $this->usuarioService->tornarAdmin($id);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Usuário promovido a administrador com sucesso',
                'usuario' => $usuario,
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário promovido a administrador com sucesso!');
    }

    public function removerAdmin(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $usuario = $this->usuarioService->removerAdmin($id);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Acesso de administrador removido com sucesso',
                'usuario' => $usuario,
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Acesso de administrador removido com sucesso!');
    }

    public function desativar(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $usuario = $this->usuarioService->desativar($id);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Usuário desativado com sucesso',
                'usuario' => $usuario,
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário desativado com sucesso!');
    }

    public function reativar(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $usuario = $this->usuarioService->reativar($id);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Usuário reativado com sucesso',
                'usuario' => $usuario,
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário reativado com sucesso!');
    }

    public function redefinirSenha(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $resultado = $this->usuarioService->redefinirSenha($id);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Nova senha temporária enviada por e-mail com sucesso',
                'usuario' => $resultado['usuario'],
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Nova senha temporária enviada com sucesso para o e-mail do usuário!');
    }

    public function atualizar(AtualizarUsuarioRequest $request, int $id): JsonResponse|RedirectResponse
    {
        $usuario = $this->usuarioService->atualizar($id, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Usuário atualizado com sucesso',
                'dados' => $usuario,
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function deletar(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $this->usuarioService->deletar($id);

        if ($request->wantsJson()) {
            return response()->json(['mensagem' => 'Usuário removido com sucesso']);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário removido com sucesso!');
    }
}