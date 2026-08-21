<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtualizarUsuarioRequest;
use App\Http\Requests\CriarUsuarioRequest;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    public function __construct(
        protected UsuarioService $usuarioService
    ) {}

    public function listar(): JsonResponse
    {
        return response()->json($this->usuarioService->listarTodos());
    }

    public function buscarPorId(int $id): JsonResponse
    {
        return response()->json($this->usuarioService->buscarPorId($id));
    }

    public function salvar(CriarUsuarioRequest $request): JsonResponse
    {
        $usuario = $this->usuarioService->criar($request->validated());

        return response()->json($usuario, 201);
    }

    public function atualizar(AtualizarUsuarioRequest $request, int $id): JsonResponse
    {
        $usuario = $this->usuarioService->atualizar($id, $request->validated());

        return response()->json([
            'mensagem' => 'Usuário atualizado com sucesso',
            'dados' => $usuario,
        ]);
    }

    public function deletar(int $id): JsonResponse
    {
        $this->usuarioService->deletar($id);

        return response()->json(['mensagem' => 'Usuário removido com sucesso']);
    }
}