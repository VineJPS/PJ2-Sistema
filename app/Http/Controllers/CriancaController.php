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

    // (DD) 9XXXX-XXXX para celulares ou (DD) XXXX-XXXX para fixos
    public const TELEFONE_REGEX = '/^\([1-9]\d\) (?:9\d{4}|[2-5]\d{3})-\d{4}$/';

    // Mantém só os dígitos e reaplica a máscara
    public static function formatarTelefone(mixed $valor): mixed
    {
        if (!is_string($valor)) {
            return $valor;
        }

        $d = preg_replace('/\D/', '', $valor);
        $d = ltrim($d, '0');
        if (strlen($d) > 11 && str_starts_with($d, '55')) {
            $d = substr($d, 2);
        }

        return match (strlen($d)) {
            11 => sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7)),
            10 => sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6)),
            default => $valor,
        };
    }

    // Normaliza o telefone (e os de listas aninhadas) dentro da requisição antes de validar.
    public static function normalizarTelefones(Request $request, ?string $lista = null): void
    {
        if ($lista === null) {
            if ($request->has('telefone')) {
                $request->merge(['telefone' => self::formatarTelefone($request->input('telefone'))]);
            }
            return;
        }

        $itens = $request->input($lista);
        if (!is_array($itens)) {
            return;
        }

        foreach ($itens as $i => $item) {
            if (is_array($item) && array_key_exists('telefone', $item)) {
                $itens[$i]['telefone'] = self::formatarTelefone($item['telefone']);
            }
        }
        $request->merge([$lista => $itens]);
    }

    public function foto(Crianca $crianca)
    {
        abort_unless($crianca->foto_path && Storage::disk('public')->exists($crianca->foto_path), 404);

        return Storage::disk('public')->response($crianca->foto_path);
    }

    public function atualizarFoto(Request $request, Crianca $crianca): JsonResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ], [
            'foto.required' => 'Selecione uma imagem.',
            'foto.image' => 'Envie uma imagem PNG ou JPG.',
            'foto.mimes' => 'Envie uma imagem PNG ou JPG.',
            'foto.max' => 'A foto deve ter no máximo 5MB.',
        ]);

        $anterior = $crianca->foto_path;
        $crianca->update(['foto_path' => $request->file('foto')->store('matriculas/fotos', 'public')]);

        if ($anterior) {
            Storage::disk('public')->delete($anterior);
        }

        return response()->json(['foto' => $crianca->fresh()->fotoUrl()]);
    }

    public function excluirFoto(Crianca $crianca): JsonResponse
    {
        if ($crianca->foto_path) {
            Storage::disk('public')->delete($crianca->foto_path);
            $crianca->update(['foto_path' => null]);
        }

        return response()->json(['foto' => null]);
    }

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

        self::normalizarTelefones($request);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'telefone' => ['required', 'regex:' . self::TELEFONE_REGEX],
            'enderecoComercial' => ['required', 'string', 'max:255'],
        ], [
            'required' => 'Preencha este campo.',
            'telefone.regex' => 'Informe um telefone válido, ex: (15) 91234-5678.',
        ]);

        $responsavel->update([
            'nome' => $dados['nome'],
            'telefone' => $dados['telefone'],
            'endereco_comercial' => $dados['enderecoComercial'],
        ]);

        return response()->json(['ok' => true]);
    }

    public function atualizarAmbiente(Request $request, Crianca $crianca): JsonResponse
    {
        $dados = $request->validate([
            'responsaveis' => ['array'],
            'responsaveis.*.id' => ['required', 'integer'],
            'responsaveis.*.profissao' => ['nullable', 'string', 'max:150'],
            'responsaveis.*.religiao' => ['nullable', 'string', 'max:100'],
            'responsaveis.*.escolaridade' => ['nullable', 'string', 'max:100'],
            'responsaveis.*.salario' => ['nullable', 'string', 'max:20'],
            'membros' => ['array'],
            'membros.*.nome' => ['required', 'string', 'max:150'],
            'membros.*.parentesco' => ['required', 'string', 'max:100'],
            'membros.*.dataNascimento' => ['nullable', 'date', 'before_or_equal:today'],
            'membros.*.renda' => ['nullable', 'string', 'max:20'],
            'renda.total' => ['nullable', 'string', 'max:20'],
            'renda.perCapita' => ['nullable', 'string', 'max:20'],
            'renda.recebeBeneficio' => ['boolean'],
            'renda.nis' => ['nullable', 'string', 'max:20'],
            'renda.tipo' => ['nullable', 'string', 'max:100'],
            'renda.valor' => ['nullable', 'string', 'max:20'],
            'endereco.estado' => ['required', 'string', 'size:2'],
            'endereco.cidade' => ['required', 'string', 'max:100'],
            'endereco.bairro' => ['required', 'string', 'max:100'],
            'endereco.rua' => ['required', 'string', 'max:150'],
            'endereco.numero' => ['required', 'string', 'max:20'],
            'endereco.pontoReferencia' => ['nullable', 'string', 'max:255'],
            'endereco.moradia' => ['required', Rule::in(['Própria', 'Cedida', 'Alugada'])],
            'endereco.valorAluguel' => ['nullable', 'string', 'max:20'],
            'gastos.agua' => ['nullable', 'string', 'max:20'],
            'gastos.energia' => ['nullable', 'string', 'max:20'],
            'gastos.gas' => ['nullable', 'string', 'max:20'],
            'gastos.alimentacao' => ['nullable', 'string', 'max:20'],
        ], [
            'required' => 'Preencha os campos obrigatórios.',
            'size' => 'O estado deve ter 2 letras (UF).',
            'date' => 'Informe uma data válida.',
            'before_or_equal' => 'A data não pode ser futura.',
            'endereco.moradia.in' => 'Selecione o tipo de moradia.',
        ]);

        $idsValidos = $crianca->responsaveis()->pluck('responsaveis.id')->all();

        DB::transaction(function () use ($dados, $crianca, $idsValidos) {
            foreach ($dados['responsaveis'] ?? [] as $r) {
                if (!in_array((int) $r['id'], $idsValidos, true)) {
                    continue;
                }
                Responsavel::whereKey($r['id'])->update([
                    'profissao' => $r['profissao'] ?? null,
                    'religiao' => $r['religiao'] ?? null,
                    'grau_escolaridade' => $r['escolaridade'] ?? null,
                    'salario' => self::moeda($r['salario'] ?? null),
                ]);
            }

            $crianca->membrosFamiliares()->delete();
            foreach ($dados['membros'] ?? [] as $m) {
                $crianca->membrosFamiliares()->create([
                    'nome' => $m['nome'],
                    'parentesco' => $m['parentesco'],
                    'data_nascimento' => $m['dataNascimento'] ?? null,
                    'renda' => self::moeda($m['renda'] ?? null),
                ]);
            }

            $renda = $dados['renda'] ?? [];
            $recebe = (bool) ($renda['recebeBeneficio'] ?? false);
            $crianca->rendaFamiliar()->updateOrCreate([], [
                'renda_total' => self::moeda($renda['total'] ?? null) ?? 0,
                'renda_per_capita' => self::moeda($renda['perCapita'] ?? null) ?? 0,
                'recebe_beneficio' => $recebe,
                'beneficio_nis' => $recebe ? ($renda['nis'] ?? null) : null,
                'beneficio_tipo' => $recebe ? ($renda['tipo'] ?? null) : null,
                'beneficio_valor' => $recebe ? self::moeda($renda['valor'] ?? null) : null,
            ]);

            $e = $dados['endereco'];
            $endereco = $crianca->endereco ?? new Endereco();
            $endereco->fill([
                'estado' => strtoupper($e['estado']),
                'cidade' => $e['cidade'],
                'bairro' => $e['bairro'],
                'rua' => $e['rua'],
                'numero' => $e['numero'],
                'ponto_referencia' => $e['pontoReferencia'] ?? null,
                'moradia' => $e['moradia'],
            ])->save();

            if (!$crianca->endereco_id) {
                $crianca->update(['endereco_id' => $endereco->id]);
            }

            $g = $dados['gastos'] ?? [];
            $endereco->gasto()->updateOrCreate([], [
                'valor_aluguel' => $e['moradia'] === 'Alugada' ? self::moeda($e['valorAluguel'] ?? null) : null,
                'agua' => self::moeda($g['agua'] ?? null),
                'energia' => self::moeda($g['energia'] ?? null),
                'gas' => self::moeda($g['gas'] ?? null),
                'alimentacao' => self::moeda($g['alimentacao'] ?? null),
            ]);
        });

        return response()->json([
            'ambiente' => $crianca->fresh()->ambiente(),
            'dados' => Crianca::dadosTela($crianca->id),
        ]);
    }

    private static function moeda(?string $valor): ?float
    {
        if (blank($valor)) {
            return null;
        }

        return (float) str_replace(',', '.', preg_replace('/[^\d,]/', '', $valor));
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
        self::normalizarTelefones($request);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'parentesco' => ['required', 'string', 'max:100'],
            'telefone' => ['required', 'regex:' . self::TELEFONE_REGEX],
        ], [
            'required' => 'Preencha este campo.',
            'telefone.regex' => 'Informe um telefone válido, ex: (15) 91234-5678.',
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
