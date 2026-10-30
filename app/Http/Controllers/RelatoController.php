<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRelatoRequest;
use App\Http\Requests\UpdateRelatoRequest;
use App\Services\RelatoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RelatoController extends Controller
{
    public function __construct(
        protected RelatoService $relatoService
    ) {}

    /**
     * Exibe a lista de relatos cadastrados.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $relatos = $this->relatoService->listarPaginado($search, 10);

        if ($request->wantsJson()) {
            return response()->json($relatos);
        }

        return view('relatos.index', compact('relatos', 'search'));
    }

    /**
     * Exibe o formulário de cadastro de novo relato.
     */
    public function create(): View
    {
        $criancas = $this->relatoService->obterCriancasParaSelecao();

        return view('relatos.create', compact('criancas'));
    }

    /**
     * Salva o novo relato no banco de dados.
     */
    public function store(StoreRelatoRequest $request): RedirectResponse|JsonResponse
    {
        $relato = $this->relatoService->criar($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Relato registrado com sucesso!',
                'relato' => $relato,
            ], 201);
        }

        return redirect()->route('relatos.index')->with('success', 'Relato registrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um relato específico.
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        $relato = $this->relatoService->buscarPorId($id);

        if ($request->wantsJson()) {
            return response()->json($relato);
        }

        return view('relatos.show', compact('relato'));
    }

    /**
     * Exibe o formulário de edição de um relato existente.
     */
    public function edit(int $id): View
    {
        $relato = $this->relatoService->buscarPorId($id);
        $criancas = $this->relatoService->obterCriancasParaSelecao();

        return view('relatos.edit', compact('relato', 'criancas'));
    }

    /**
     * Atualiza as informações do relato no banco de dados.
     */
    public function update(UpdateRelatoRequest $request, int $id): RedirectResponse|JsonResponse
    {
        $relato = $this->relatoService->atualizar($id, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Relato atualizado com sucesso!',
                'relato' => $relato,
            ]);
        }

        return redirect()->route('relatos.index')->with('success', 'Relato atualizado com sucesso!');
    }

    /**
     * Remove um relato do banco de dados.
     */
    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $this->relatoService->excluir($id);

        if ($request->wantsJson()) {
            return response()->json([
                'mensagem' => 'Relato excluído com sucesso!',
            ]);
        }

        return redirect()->route('relatos.index')->with('success', 'Relato excluído com sucesso!');
    }
}
