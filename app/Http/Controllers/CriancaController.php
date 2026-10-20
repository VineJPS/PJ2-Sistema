<?php

namespace App\Http\Controllers;

use App\Models\Crianca;
use App\Models\Endereco;
use App\Models\PessoaAutorizada;
use App\Models\Responsavel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CriancaController extends Controller
{
    private const STATUS = ['Ativo', 'Inativo', 'Pendente'];

    public function atualizarNome(Request $request, Crianca $crianca): JsonResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
        ], ['required' => 'Informe o nome da criança.']);

        $crianca->update($dados);

        return response()->json(['nome' => $crianca->nome]);
    }

    public function atualizarStatus(Request $request, Crianca $crianca): JsonResponse
    {
        $dados = $request->validate([
            'status' => ['required', Rule::in(self::STATUS)],
        ]);

        $crianca->update($dados);

        return response()->json(['status' => $crianca->status]);
    }

    public function excluir(Crianca $crianca): JsonResponse
    {
        DB::transaction(function () use ($crianca) {
            $crianca->load(['responsaveis', 'assinatura']);
            $enderecoId = $crianca->endereco_id;
            $arquivos = array_filter([$crianca->foto_path, $crianca->assinatura?->arquivo_path]);
            $responsaveisIds = $crianca->responsaveis->pluck('id');

            // As tabelas dependentes caem em cascata junto com a criança
            $crianca->delete();

            // Responsáveis que não estão ligados a outra criança deixam de existir
            Responsavel::whereIn('id', $responsaveisIds)
                ->whereDoesntHave('criancas')
                ->delete();

            // O endereço só é removido quando ninguém mais o utiliza
            $emUso = Crianca::where('endereco_id', $enderecoId)->exists()
                || Responsavel::where('endereco_id', $enderecoId)->exists();
            if (!$emUso) {
                Endereco::whereKey($enderecoId)->delete();
            }

            if ($arquivos) {
                Storage::disk('public')->delete($arquivos);
            }
        });

        return response()->json(['removida' => true]);
    }

    /** Aba Matrícula: dados pessoais, endereço residencial e informações escolares. */
    public function atualizarDados(Request $request, Crianca $crianca): JsonResponse
    {
        $dados = $request->validate([
            'dataNascimento' => ['required', 'date_format:d/m/Y', 'before_or_equal:today'],
            'cpf' => ['nullable', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', Rule::unique('criancas', 'cpf')->ignore($crianca->id)],
            'rg' => ['nullable', 'string', 'max:20'],
            'escola' => ['nullable', 'string', 'max:150'],
            'periodo' => ['nullable', 'in:Matutino,Vespertino,Integral'],
            'serie' => ['nullable', 'string', 'max:50'],
            'classeBetel' => ['nullable', 'in:Turma 1,Turma 2,Turma 3'],
            'enderecoCampos.rua' => ['required', 'string', 'max:150'],
            'enderecoCampos.numero' => ['required', 'string', 'max:20'],
            'enderecoCampos.bairro' => ['required', 'string', 'max:100'],
            'enderecoCampos.cidade' => ['required', 'string', 'max:100'],
            'enderecoCampos.estado' => ['required', 'regex:/^[A-Za-z]{2}$/'],
        ], [
            'required' => 'Preencha todos os campos obrigatórios.',
            'dataNascimento.date_format' => 'Informe a data no formato dd/mm/aaaa.',
            'dataNascimento.before_or_equal' => 'A data não pode ser futura.',
            'cpf.regex' => 'Informe o CPF no formato 000.000.000-00.',
            'cpf.unique' => 'Já existe uma criança com este CPF.',
            'periodo.in' => 'Período inválido.',
            'classeBetel.in' => 'Classe inválida.',
            'enderecoCampos.estado.regex' => 'Informe a sigla do estado (ex: SP).',
        ]);

        DB::transaction(function () use ($crianca, $dados) {
            $crianca->update([
                'data_nascimento' => Carbon::createFromFormat('d/m/Y', $dados['dataNascimento'])->toDateString(),
                'cpf' => $dados['cpf'] ?? null,
                'rg' => $dados['rg'] ?? null,
                'escola' => $dados['escola'] ?? null,
                'periodo' => $dados['periodo'] ?? null,
                'serie' => $dados['serie'] ?? null,
                'turma_betel' => $dados['classeBetel'] ?? null,
            ]);

            $crianca->endereco->update([
                'rua' => $dados['enderecoCampos']['rua'],
                'numero' => $dados['enderecoCampos']['numero'],
                'bairro' => $dados['enderecoCampos']['bairro'],
                'cidade' => $dados['enderecoCampos']['cidade'],
                'estado' => strtoupper($dados['enderecoCampos']['estado']),
            ]);
        });

        return response()->json(['dados' => Crianca::dadosTela($crianca->id)]);
    }

    public function atualizarResponsavel(Request $request, Crianca $crianca, Responsavel $responsavel): JsonResponse
    {
        abort_unless($crianca->responsaveis()->whereKey($responsavel->id)->exists(), 404);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'telefone' => ['required', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'enderecoComercial' => ['nullable', 'string', 'max:255'],
        ], [
            'required' => 'Preencha este campo.',
            'telefone.regex' => 'Informe o telefone no formato (00) 00000-0000.',
        ]);

        $responsavel->update([
            'nome' => $dados['nome'],
            'telefone' => $dados['telefone'],
            'endereco_comercial' => $dados['enderecoComercial'] ?? null,
        ]);

        return response()->json(['ok' => true]);
    }

    public function atualizarPermissoes(Request $request, Crianca $crianca): JsonResponse
    {
        $dados = $request->validate([
            'campo' => ['required', Rule::in(['autoriza_frequencia', 'sai_sozinho'])],
            'valor' => ['required', 'boolean'],
        ]);

        $crianca->autorizacao()->firstOrCreate([], [
            'autoriza_frequencia' => false,
            'sai_sozinho' => false,
        ])->update([$dados['campo'] => $request->boolean('valor')]);

        return response()->json(['ok' => true]);
    }

    public function criarPessoaAutorizada(Request $request, Crianca $crianca): JsonResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'parentesco' => ['required', 'string', 'max:100'],
            'telefone' => ['required', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
        ], [
            'required' => 'Preencha este campo.',
            'telefone.regex' => 'Informe o telefone no formato (00) 00000-0000.',
        ]);

        $autorizacao = $crianca->autorizacao()->firstOrCreate([], [
            'autoriza_frequencia' => false,
            'sai_sozinho' => false,
        ]);
        $pessoa = $autorizacao->pessoasAutorizadas()->create($dados);

        return response()->json([
            'id' => $pessoa->id,
            'nome' => $pessoa->nome,
            'parentesco' => $pessoa->parentesco,
            'telefone' => $pessoa->telefone,
        ], 201);
    }

    public function excluirPessoaAutorizada(Crianca $crianca, PessoaAutorizada $pessoa): JsonResponse
    {
        abort_unless($pessoa->autorizacao?->crianca_id === $crianca->id, 404);

        $pessoa->delete();

        return response()->json(['removida' => true]);
    }

    public function atualizarAssinatura(Request $request, Crianca $crianca): JsonResponse
    {
        $request->validate([
            'assinatura' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ], [
            'assinatura.required' => 'Envie a imagem da assinatura.',
            'assinatura.image' => 'Envie uma imagem PNG ou JPG.',
            'assinatura.mimes' => 'Envie uma imagem PNG ou JPG.',
            'assinatura.max' => 'A assinatura deve ter no máximo 5MB.',
        ]);

        $assinatura = $crianca->assinatura()->firstOrNew([]);
        if ($assinatura->arquivo_path) {
            Storage::disk('public')->delete($assinatura->arquivo_path);
        }

        $assinatura->fill([
            'termo_aceito' => $assinatura->termo_aceito ?? true,
            'data_assinatura' => now()->toDateString(),
            'arquivo_path' => $request->file('assinatura')->store('matriculas/assinaturas', 'public'),
        ])->save();

        return response()->json(['dataAssinatura' => $assinatura->data_assinatura->format('d/m/Y')]);
    }
}
