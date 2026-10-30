<?php

namespace App\Services;

use App\Models\Crianca;
use App\Models\Relato;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class RelatoService
{
    /**
     * Lista relatos paginados com suporte a busca textual.
     */
    public function listarPaginado(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Relato::with('usuario');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nome_crianca', 'like', "%{$search}%")
                  ->orWhere('nome_funcionario', 'like', "%{$search}%")
                  ->orWhere('relato', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('data_ocorrido', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Busca um relato específico pelo ID.
     */
    public function buscarPorId(int $id): Relato
    {
        return Relato::with('usuario')->findOrFail($id);
    }

    /**
     * Cria um novo relato associando ao usuário autenticado.
     */
    public function criar(array $dados): Relato
    {
        if (empty($dados['FK_id_usuario']) && Auth::check()) {
            $dados['FK_id_usuario'] = Auth::id();
        }

        return Relato::create($dados);
    }

    /**
     * Atualiza os dados de um relato existente.
     */
    public function atualizar(int|Relato $relato, array $dados): Relato
    {
        $model = $relato instanceof Relato ? $relato : $this->buscarPorId($relato);

        $model->update($dados);

        return $model->fresh('usuario');
    }

    /**
     * Remove um relato do banco de dados.
     */
    public function excluir(int|Relato $relato): bool
    {
        $model = $relato instanceof Relato ? $relato : $this->buscarPorId($relato);

        return (bool) $model->delete();
    }

    /**
     * Retorna a lista de crianças cadastradas para sugestão/autocomplete.
     */
    public function obterCriancasParaSelecao(): Collection
    {
        return Crianca::orderBy('nome')->get(['id', 'nome']);
    }
}
