<?php

namespace App\Http\Controllers;

use App\Http\Requests\Matricula\AutorizacoesRequest;
use App\Http\Requests\Matricula\CriancaRequest;
use App\Http\Requests\Matricula\EnderecoRequest;
use App\Http\Requests\Matricula\ResponsaveisRequest;
use App\Http\Requests\Matricula\SaudeRequest;
use App\Services\MatriculaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MatriculaController extends Controller
{
    public function __construct(private readonly MatriculaService $matriculaService)
    {
    }

    private const SESSAO_CRIANCA = 'matricula.crianca';
    private const SESSAO_RESPONSAVEIS = 'matricula.responsaveis';
    private const SESSAO_ENDERECO = 'matricula.endereco';
    private const SESSAO_SAUDE = 'matricula.saude';
    private const SESSAO_AUTORIZACOES = 'matricula.autorizacoes';

    public function mostrarCrianca(): View
    {
        return view('components.secoes.matriculas.cadastroCrianca.cadastroCrianca', [
            'dados' => session(self::SESSAO_CRIANCA, []),
        ]);
    }

    public function fotoRascunho()
    {
        $caminho = session(self::SESSAO_CRIANCA . '.foto_path');
        $disco = \Illuminate\Support\Facades\Storage::disk('public');

        abort_unless($caminho && $disco->exists($caminho), 404);

        return $disco->response($caminho);
    }

    public function salvarCrianca(CriancaRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        unset($dados['foto']);

        $fotoAtual = session(self::SESSAO_CRIANCA . '.foto_path');

        if ($request->hasFile('foto')) {
            $dados['foto_path'] = $request->file('foto')->store('matriculas/fotos', 'public');
            if ($fotoAtual) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoAtual);
            }
        } elseif ($request->boolean('remover_foto')) {
            if ($fotoAtual) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoAtual);
            }
            $dados['foto_path'] = null;
        } else {
            $dados['foto_path'] = $fotoAtual;
        }

        session([self::SESSAO_CRIANCA => $dados]);

        return redirect()->route('matricula.responsaveis');
    }

    public function mostrarResponsaveis(): View|RedirectResponse
    {
        if (!session()->has(self::SESSAO_CRIANCA)) {
            return redirect()->route('matricula.crianca');
        }

        return view('components.secoes.matriculas.cadastroResposaveis.cadastroResponsaveis', [
            'dados' => session(self::SESSAO_RESPONSAVEIS, []),
        ]);
    }

    public function salvarResponsaveis(ResponsaveisRequest $request): RedirectResponse
    {
        if (!session()->has(self::SESSAO_CRIANCA)) {
            return redirect()->route('matricula.crianca');
        }

        $dados = $request->validated();

        if (!$dados['recebe_beneficio']) {
            $dados['beneficio_nis'] = $dados['beneficio_tipo'] = $dados['beneficio_valor'] = null;
        }

        session([self::SESSAO_RESPONSAVEIS => $dados]);

        return redirect()->route('matricula.endereco');
    }

    public function mostrarEndereco(): View|RedirectResponse
    {
        if (!session()->has(self::SESSAO_CRIANCA)) {
            return redirect()->route('matricula.crianca');
        }

        if (!session()->has(self::SESSAO_RESPONSAVEIS)) {
            return redirect()->route('matricula.responsaveis');
        }

        return view('components.secoes.matriculas.cadastroEnderecoGastos.cadastroEnderecoGastos', [
            'dados' => session(self::SESSAO_ENDERECO, []),
        ]);
    }

    public function salvarEndereco(EnderecoRequest $request): RedirectResponse
    {
        if (!session()->has(self::SESSAO_CRIANCA)) {
            return redirect()->route('matricula.crianca');
        }

        if (!session()->has(self::SESSAO_RESPONSAVEIS)) {
            return redirect()->route('matricula.responsaveis');
        }

        $dados = $request->validated();

        if ($dados['tipo_moradia'] !== 'Alugada') {
            $dados['valor_aluguel'] = null;
        }

        session([self::SESSAO_ENDERECO => $dados]);

        return redirect()->route('matricula.saude');
    }

    public function mostrarSaude(): View|RedirectResponse
    {
        if ($pendente = $this->etapaPendente([self::SESSAO_CRIANCA, self::SESSAO_RESPONSAVEIS, self::SESSAO_ENDERECO])) {
            return $pendente;
        }

        return view('components.secoes.matriculas.fichaSaude.fichaSaude', [
            'dados' => session(self::SESSAO_SAUDE, []),
        ]);
    }

    public function salvarSaude(SaudeRequest $request): RedirectResponse
    {
        if ($pendente = $this->etapaPendente([self::SESSAO_CRIANCA, self::SESSAO_RESPONSAVEIS, self::SESSAO_ENDERECO])) {
            return $pendente;
        }

        $dados = $request->validated();

        // Upload da assinatura
        $assinaturaAnterior = session(self::SESSAO_SAUDE . '.assinatura_path');
        unset($dados['assinatura']);

        if ($request->hasFile('assinatura')) {
            $dados['assinatura_path'] = $request->file('assinatura')->store('matriculas/saude-assinaturas', 'public');
            if ($assinaturaAnterior) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($assinaturaAnterior);
            }
        } else {
            $dados['assinatura_path'] = $assinaturaAnterior;
        }

        session([self::SESSAO_SAUDE => $dados]);

        return redirect()->route('matricula.autorizacoes');
    }

    public function mostrarAutorizacoes(): View|RedirectResponse
    {
        if ($pendente = $this->etapaPendente([self::SESSAO_CRIANCA, self::SESSAO_RESPONSAVEIS, self::SESSAO_ENDERECO, self::SESSAO_SAUDE])) {
            return $pendente;
        }

        return view('components.secoes.matriculas.cadastroAutorizacoesAssinaturas.cadastroAutorizacoesAssinaturas', [
            'dados' => session(self::SESSAO_AUTORIZACOES, []),
        ]);
    }

    public function salvarAutorizacoes(AutorizacoesRequest $request): RedirectResponse
    {
        if ($pendente = $this->etapaPendente([self::SESSAO_CRIANCA, self::SESSAO_RESPONSAVEIS, self::SESSAO_ENDERECO, self::SESSAO_SAUDE])) {
            return $pendente;
        }

        $assinaturaAnterior = session(self::SESSAO_AUTORIZACOES . '.assinatura_path');

        $dados = $request->validated();

        // Sem permissão para sair sozinha, ao menos uma pessoa autorizada é obrigatória
        if (!$dados['sai_sozinho'] && empty($dados['autorizados'])) {
            return back()->withInput()->withErrors([
                'autorizados' => 'Informe ao menos uma pessoa autorizada a buscar a criança.',
            ]);
        }

        unset($dados['assinatura']);
        $dados['autorizados'] = $dados['autorizados'] ?? [];
        $dados['assinatura_path'] = $request->hasFile('assinatura')
            ? $request->file('assinatura')->store('matriculas/assinaturas', 'public')
            : $assinaturaAnterior;

        $pendencias = $this->calcularPendencias($dados);

        $this->matriculaService->registrar(
            session(self::SESSAO_CRIANCA),
            session(self::SESSAO_RESPONSAVEIS),
            session(self::SESSAO_ENDERECO),
            session(self::SESSAO_SAUDE, []),
            $dados,
            $pendencias,
        );

        session()->forget([
            self::SESSAO_CRIANCA,
            self::SESSAO_RESPONSAVEIS,
            self::SESSAO_ENDERECO,
            self::SESSAO_SAUDE,
            self::SESSAO_AUTORIZACOES,
        ]);

        $mensagem = $pendencias
            ? 'Cadastro concluído com status Pendente.'
            : 'Cadastro concluído com sucesso!';

        return redirect()->route('home')->with('success', $mensagem);
    }

    /** Campos opcionais não preenchidos deixam a criança com status Pendente. */
    private function calcularPendencias(array $autorizacoes): array
    {
        $crianca = session(self::SESSAO_CRIANCA, []);
        $responsaveis = session(self::SESSAO_RESPONSAVEIS . '.responsaveis', []);
        $endereco = session(self::SESSAO_ENDERECO, []);
        $pendencias = [];

        $faltam = fn (array $origem, array $campos) => array_filter(
            $campos,
            fn ($campo) => blank($origem[$campo] ?? null)
        );

        if (blank($crianca['foto_path'] ?? null)) {
            $pendencias[] = 'foto';
        }
        if ($faltam($crianca, ['cpf'])) {
            $pendencias[] = 'CPF';
        }
        if ($faltam($crianca, ['rg'])) {
            $pendencias[] = 'RG';
        }

        $camposResponsavel = [
            'escolaridade' => 'escolaridade',
            'profissao' => 'profissão',
            'religiao' => 'religião',
            'salario' => 'salário',
        ];
        foreach ($camposResponsavel as $campo => $rotulo) {
            foreach ($responsaveis as $responsavel) {
                if (blank($responsavel[$campo] ?? null)) {
                    $pendencias[] = "$rotulo do responsável";
                    break;
                }
            }
        }

        $camposEndereco = [
            'agua' => 'gasto com água',
            'energia' => 'gasto com energia',
            'alimentacao' => 'gasto com alimentação',
            'gas' => 'gasto com gás',
        ];
        foreach ($camposEndereco as $campo => $rotulo) {
            if (blank($endereco[$campo] ?? null)) {
                $pendencias[] = $rotulo;
            }
        }

        if (blank($autorizacoes['data_assinatura'] ?? null)) {
            $pendencias[] = 'data da assinatura';
        }
        if (blank($autorizacoes['assinatura_path'] ?? null)) {
            $pendencias[] = 'arquivo de assinatura';
        }

        $saude = session(self::SESSAO_SAUDE, []);
        if (blank($saude['assinatura_path'] ?? null)) {
            $pendencias[] = 'arquivo de assinatura de saúde';
        }

        return $pendencias;
    }

    private function etapaPendente(array $etapas): ?RedirectResponse
    {
        $rotas = [
            self::SESSAO_CRIANCA => 'matricula.crianca',
            self::SESSAO_RESPONSAVEIS => 'matricula.responsaveis',
            self::SESSAO_ENDERECO => 'matricula.endereco',
            self::SESSAO_SAUDE => 'matricula.saude',
        ];

        foreach ($etapas as $etapa) {
            if (!session()->has($etapa)) {
                return redirect()->route($rotas[$etapa]);
            }
        }

        return null;
    }
}
