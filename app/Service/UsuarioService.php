<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function listarTodos(): Collection
    {
        return Usuario::with('nivel')->get();
    }

    public function buscarPorId(int $id): Usuario
    {
        return Usuario::with('nivel')->findOrFail($id);
    }

    public function criar(array $dados): Usuario
    {
        $dados['senha'] = Hash::make($dados['senha']);

        return Usuario::create($dados);
    }

    public function atualizar(int $id, array $dados): Usuario
    {
        $usuario = Usuario::findOrFail($id);

        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        } else {
            unset($dados['senha']);
        }

        $usuario->update($dados);

        return $usuario;
    }

    public function deletar(int $id): bool
    {
        $usuario = Usuario::findOrFail($id);

        return $usuario->delete();
    }
}