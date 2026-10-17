<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatriculaController extends Controller
{
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

    public function salvarCrianca(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            'nome' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date', 'before_or_equal:today'],
            'cpf' => ['required', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            'rg' => ['required', 'string', 'max:20'],
            'escola' => ['required', 'string', 'max:255'],
            'periodo' => ['required', 'in:Matutino,Vespertino,Integral'],
            'serie' => ['required', 'string', 'max:50'],
            'turma_betel' => ['required', 'in:Turma 1,Turma 2,Turma 3'],
        ], [
            'required' => 'Preencha este campo.',
            'foto.image' => 'Envie uma imagem PNG ou JPG.',
            'foto.mimes' => 'Envie uma imagem PNG ou JPG.',
            'foto.max' => 'A foto deve ter no máximo 5MB.',
            'data_nascimento.date' => 'Informe uma data válida.',
            'data_nascimento.before_or_equal' => 'A data não pode ser futura.',
            'cpf.regex' => 'Informe o CPF no formato 000.000.000-00.',
            'periodo.in' => 'Selecione um período válido.',
            'turma_betel.in' => 'Selecione uma turma válida.',
        ]);

        unset($dados['foto']);

        if ($request->hasFile('foto')) {
            $dados['foto_path'] = $request->file('foto')->store('matriculas/fotos', 'public');
        } else {
            $dados['foto_path'] = session(self::SESSAO_CRIANCA . '.foto_path');
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

    public function salvarResponsaveis(Request $request): RedirectResponse
    {
        if (!session()->has(self::SESSAO_CRIANCA)) {
            return redirect()->route('matricula.crianca');
        }

        // Linhas de composição familiar totalmente vazias são descartadas antes de validar
        $request->merge([
            'membros' => collect($request->input('membros', []))
                ->filter(fn ($membro) => collect($membro)->filter(fn ($v) => filled($v))->isNotEmpty())
                ->values()
                ->all(),
        ]);

        $dados = $request->validate([
            'responsaveis' => ['required', 'array', 'min:1'],
            'responsaveis.*.nome' => ['required', 'string', 'max:255'],
            'responsaveis.*.parentesco' => ['required', 'string', 'max:100'],
            'responsaveis.*.telefone' => ['required', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'responsaveis.*.escolaridade' => ['nullable', 'string', 'max:100'],
            'responsaveis.*.salario' => ['nullable', 'string', 'max:20'],
            'responsaveis.*.profissao' => ['nullable', 'string', 'max:100'],
            'responsaveis.*.endereco_comercial' => ['nullable', 'string', 'max:255'],
            'responsaveis.*.religiao' => ['nullable', 'string', 'max:100'],
            'membros' => ['nullable', 'array'],
            'membros.*.nome' => ['required', 'string', 'max:255'],
            'membros.*.parentesco' => ['required', 'string', 'max:100'],
            'membros.*.data_nascimento' => ['nullable', 'date', 'before_or_equal:today'],
            'membros.*.renda' => ['nullable', 'string', 'max:20'],
            'renda_total' => ['required', 'string', 'max:20'],
            'renda_per_capita' => ['required', 'string', 'max:20'],
            'recebe_beneficio' => ['required', 'boolean'],
            'beneficio_nis' => ['required_if:recebe_beneficio,1', 'nullable', 'regex:/^\d{3}\.\d{5}\.\d{2}-\d$/'],
            'beneficio_tipo' => ['required_if:recebe_beneficio,1', 'nullable', 'in:Bolsa Família,Cadúnico,Outros'],
            'beneficio_valor' => ['required_if:recebe_beneficio,1', 'nullable', 'string', 'max:20'],
        ], [
            'required' => 'Preencha este campo.',
            'required_if' => 'Preencha este campo.',
            'responsaveis.required' => 'Informe ao menos um responsável.',
            '*.telefone.regex' => 'Informe o telefone no formato (00) 00000-0000.',
            'beneficio_nis.regex' => 'Informe o NIS no formato 000.00000.00-0.',
            '*.data_nascimento.date' => 'Informe uma data válida.',
            '*.data_nascimento.before_or_equal' => 'A data não pode ser futura.',
        ]);

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

    public function salvarEndereco(Request $request): RedirectResponse
    {
        if (!session()->has(self::SESSAO_CRIANCA)) {
            return redirect()->route('matricula.crianca');
        }

        if (!session()->has(self::SESSAO_RESPONSAVEIS)) {
            return redirect()->route('matricula.responsaveis');
        }

        $request->merge(['estado' => strtoupper((string) $request->input('estado'))]);

        $dados = $request->validate([
            'rua' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'regex:/^[A-Z]{2}$/'],
            'ponto_referencia' => ['nullable', 'string', 'max:255'],
            'tipo_moradia' => ['required', 'in:Própria,Cedida,Alugada'],
            'valor_aluguel' => ['required_if:tipo_moradia,Alugada', 'nullable', 'string', 'max:20'],
            'agua' => ['required', 'string', 'max:20'],
            'energia' => ['required', 'string', 'max:20'],
            'alimentacao' => ['required', 'string', 'max:20'],
            'gas' => ['required', 'string', 'max:20'],
        ], [
            'required' => 'Preencha este campo.',
            'required_if' => 'Preencha este campo.',
            'estado.regex' => 'Informe a sigla do estado (ex: SP).',
            'tipo_moradia.in' => 'Selecione o tipo de moradia.',
        ]);

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

        return view('components.secoes.matriculas.fichaSaude.fichaSaude');
    }

    public function salvarSaude(): RedirectResponse
    {
        if ($pendente = $this->etapaPendente([self::SESSAO_CRIANCA, self::SESSAO_RESPONSAVEIS, self::SESSAO_ENDERECO])) {
            return $pendente;
        }

        session([self::SESSAO_SAUDE => true]);

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

    public function salvarAutorizacoes(Request $request): RedirectResponse
    {
        if ($pendente = $this->etapaPendente([self::SESSAO_CRIANCA, self::SESSAO_RESPONSAVEIS, self::SESSAO_ENDERECO, self::SESSAO_SAUDE])) {
            return $pendente;
        }

        $request->merge([
            'autoriza_frequencia' => $request->boolean('autoriza_frequencia'),
            'sai_sozinho' => $request->boolean('sai_sozinho'),
            'termo_aceito' => $request->boolean('termo_aceito'),
        ]);

        $assinaturaAnterior = session(self::SESSAO_AUTORIZACOES . '.assinatura_path');

        $dados = $request->validate([
            'autoriza_frequencia' => ['boolean'],
            'sai_sozinho' => ['boolean'],
            'autorizados' => ['array'],
            'autorizados.*.nome' => ['required', 'string', 'max:255'],
            'autorizados.*.parentesco' => ['required', 'string', 'max:100'],
            'autorizados.*.telefone' => ['required', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'termo_aceito' => ['accepted'],
            'data_assinatura' => ['required', 'date', 'before_or_equal:today'],
            'assinatura' => [$assinaturaAnterior ? 'nullable' : 'required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ], [
            'required' => 'Preencha este campo.',
            'termo_aceito.accepted' => 'É necessário aceitar o termo de compromisso.',
            'data_assinatura.date' => 'Informe uma data válida.',
            'data_assinatura.before_or_equal' => 'A data não pode ser futura.',
            'assinatura.required' => 'Envie a assinatura do responsável.',
            'assinatura.image' => 'Envie uma imagem PNG ou JPG.',
            'assinatura.mimes' => 'Envie uma imagem PNG ou JPG.',
            'assinatura.max' => 'A assinatura deve ter no máximo 5MB.',
            '*.telefone.regex' => 'Informe o telefone no formato (00) 00000-0000.',
        ]);

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

        // Sem banco por enquanto: encerra o fluxo limpando os dados da sessão
        session()->forget([
            self::SESSAO_CRIANCA,
            self::SESSAO_RESPONSAVEIS,
            self::SESSAO_ENDERECO,
            self::SESSAO_SAUDE,
            self::SESSAO_AUTORIZACOES,
        ]);

        return redirect()->route('home')
            ->with('success', 'Cadastro concluído com sucesso!');
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
