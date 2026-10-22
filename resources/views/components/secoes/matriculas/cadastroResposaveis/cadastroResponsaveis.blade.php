<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsáveis e família - Recanto Betel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/cadastroCrianca.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroResponsaveis.css') }}">
</head>
@php
    $origem = old() ? old() : ($dados ?? []);
    $inicial = [
        'responsaveis' => collect($origem['responsaveis'] ?? [])->map(fn ($r) => [
            'nome' => $r['nome'] ?? '', 'parentesco' => $r['parentesco'] ?? '', 'telefone' => $r['telefone'] ?? '',
            'escolaridade' => $r['escolaridade'] ?? '', 'salario' => $r['salario'] ?? '', 'profissao' => $r['profissao'] ?? '',
            'enderecoComercial' => $r['endereco_comercial'] ?? '', 'religiao' => $r['religiao'] ?? '',
        ])->values()->all(),
        'membros' => collect($origem['membros'] ?? [])->map(fn ($m) => [
            'nome' => $m['nome'] ?? '', 'parentesco' => $m['parentesco'] ?? '',
            'dataNascimento' => $m['data_nascimento'] ?? '', 'renda' => $m['renda'] ?? '',
        ])->values()->all(),
        'renda' => [
            'total' => $origem['renda_total'] ?? '', 'perCapita' => $origem['renda_per_capita'] ?? '',
            'recebeBeneficio' => (bool) ($origem['recebe_beneficio'] ?? false),
            'nis' => $origem['beneficio_nis'] ?? '', 'tipo' => $origem['beneficio_tipo'] ?? '', 'valor' => $origem['beneficio_valor'] ?? '',
        ],
    ];
@endphp
<body class="cadastro-body">
    <div class="cadastro-layout">
        <x-sidebar />

        <main class="cadastro-main">
            <div class="cadastro-wrap cadastro-wrap-largo" x-data="responsaveisFamiliaComponent(@js($inicial))">
                <header class="cadastro-heading">
                    <h1>Responsáveis e família</h1>
                    <p>Pai, mãe (ou responsáveis legais) e quem mais mora com a criança.</p>
                </header>

                @if ($errors->any())
                    <div class="aviso-erro" role="alert">
                        <strong>Revise os campos abaixo:</strong>
                        <ul>
                            @foreach (array_unique($errors->all()) as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="cadastro-form" method="POST" action="{{ route('matricula.responsaveis.salvar') }}" aria-label="Responsáveis e família">
                    @csrf

                    <!-- 1. Responsáveis -->
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero">1</span>
                            <div>
                                <h2>Responsáveis</h2>
                                <p>Insira quem pode buscar a criança na instituição.</p>
                            </div>
                        </div>

                        <template x-for="(item, i) in responsaveis" :key="item.id">
                            <div class="bloco-item">
                                <button type="button" class="btn-remover" aria-label="Remover responsável" x-show="responsaveis.length > 1" @click="remover(responsaveis, i)">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-12M9 7V4h6v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>

                                <div class="cadastro-fields">
                                    <div class="field field-half">
                                        <label :for="'resp-nome-' + item.id">Nome completo</label>
                                        <input :id="'resp-nome-' + item.id" :name="`responsaveis[${i}][nome]`" type="text" placeholder="Nome completo" required x-model="item.nome">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'resp-parentesco-' + item.id">Grau de parentesco</label>
                                        <input :id="'resp-parentesco-' + item.id" :name="`responsaveis[${i}][parentesco]`" type="text" placeholder="Ex: Mãe, Pai, Tia" required x-model="item.parentesco">
                                    </div>

                                    <div class="field field-half">
                                        <label :for="'resp-telefone-' + item.id">Telefone</label>
                                        <input :id="'resp-telefone-' + item.id" :name="`responsaveis[${i}][telefone]`" type="tel" inputmode="tel" placeholder="(15) 91234-5678" required x-model="item.telefone" x-init="item.telefone = mascararTelefone(item.telefone || ''); $el.value = item.telefone" @input="$event.target.value = item.telefone = mascararTelefone($event.target.value)">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'resp-escolaridade-' + item.id">Grau de escolaridade</label>
                                        <input :id="'resp-escolaridade-' + item.id" :name="`responsaveis[${i}][escolaridade]`" type="text" placeholder="Ex: Ensino médio" x-model="item.escolaridade">
                                    </div>

                                    <div class="field field-half">
                                        <label :for="'resp-salario-' + item.id">Salário</label>
                                        <input :id="'resp-salario-' + item.id" :name="`responsaveis[${i}][salario]`" type="text" inputmode="numeric" placeholder="R$ 0,00" x-model="item.salario" @input="item.salario = mascararMoeda($event.target.value)">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'resp-profissao-' + item.id">Profissão</label>
                                        <input :id="'resp-profissao-' + item.id" :name="`responsaveis[${i}][profissao]`" type="text" placeholder="Profissão atual" x-model="item.profissao">
                                    </div>

                                    <div class="field field-half">
                                        <label :for="'resp-endereco-' + item.id">Endereço Comercial</label>
                                        <input :id="'resp-endereco-' + item.id" :name="`responsaveis[${i}][endereco_comercial]`" type="text" placeholder="Endereço do trabalho" required x-model="item.enderecoComercial">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'resp-religiao-' + item.id">Religião</label>
                                        <input :id="'resp-religiao-' + item.id" :name="`responsaveis[${i}][religiao]`" type="text" placeholder="Religião" x-model="item.religiao">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button type="button" class="btn-adicionar" @click="adicionarResponsavel()">
                            <span aria-hidden="true">+</span> Adicionar Responsável
                        </button>
                    </section>

                    <!-- 2. Composição familiar -->
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero">2</span>
                            <div>
                                <h2>Composição familiar</h2>
                                <p>Outras pessoas que moram na mesma casa.</p>
                            </div>
                        </div>

                        <template x-for="(item, i) in membros" :key="item.id">
                            <div class="bloco-item">
                                <button type="button" class="btn-remover" aria-label="Remover membro da família" @click="remover(membros, i)">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-12M9 7V4h6v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>

                                <div class="cadastro-fields">
                                    <div class="field field-half">
                                        <label :for="'membro-nome-' + item.id">Nome completo</label>
                                        <input :id="'membro-nome-' + item.id" :name="`membros[${i}][nome]`" type="text" placeholder="Nome completo" x-model="item.nome">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'membro-parentesco-' + item.id">Grau de parentesco</label>
                                        <input :id="'membro-parentesco-' + item.id" :name="`membros[${i}][parentesco]`" type="text" placeholder="Ex: Mãe, Pai, Tia" x-model="item.parentesco">
                                    </div>

                                    <div class="field field-half">
                                        <label :for="'membro-nascimento-' + item.id">Data de nascimento</label>
                                        <input :id="'membro-nascimento-' + item.id" :name="`membros[${i}][data_nascimento]`" type="date" x-model="item.dataNascimento">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'membro-renda-' + item.id">Renda</label>
                                        <input :id="'membro-renda-' + item.id" :name="`membros[${i}][renda]`" type="text" inputmode="numeric" placeholder="R$ 0,00" x-model="item.renda" @input="item.renda = mascararMoeda($event.target.value)">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button type="button" class="btn-adicionar" @click="adicionarMembro()">
                            <span aria-hidden="true">+</span> Adicionar membro da família
                        </button>
                    </section>

                    <!-- 3. Renda familiar -->
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero">3</span>
                            <div>
                                <h2>Renda familiar</h2>
                                <p>Total da casa, considerando todos os rendimentos.</p>
                            </div>
                        </div>

                        <div class="cadastro-fields">
                            <div class="field field-half">
                                <label for="renda-total">Renda total</label>
                                <input id="renda-total" name="renda_total" type="text" inputmode="numeric" placeholder="R$ 0,00" required x-model="renda.total" @input="renda.total = mascararMoeda($event.target.value)">
                            </div>
                            <div class="field field-half">
                                <label for="renda-per-capita">Renda percapita</label>
                                <input id="renda-per-capita" name="renda_per_capita" type="text" inputmode="numeric" placeholder="R$ 0,00" required x-model="renda.perCapita" @input="renda.perCapita = mascararMoeda($event.target.value)">
                            </div>
                        </div>

                        <div class="pergunta-beneficio">
                            <span id="rotulo-beneficio">Recebe algum benefício do governo?</span>
                            <div class="toggle-sim-nao" role="group" aria-labelledby="rotulo-beneficio">
                                <button type="button" :class="{ 'ativo': !renda.recebeBeneficio }" :aria-pressed="!renda.recebeBeneficio" @click="renda.recebeBeneficio = false">Não</button>
                                <button type="button" :class="{ 'ativo': renda.recebeBeneficio }" :aria-pressed="renda.recebeBeneficio" @click="renda.recebeBeneficio = true">Sim</button>
                            </div>
                            <input type="hidden" name="recebe_beneficio" :value="renda.recebeBeneficio ? 1 : 0">
                        </div>

                        <div class="cadastro-fields beneficio-campos" x-show="renda.recebeBeneficio" x-cloak>
                            <div class="field field-nis">
                                <label for="beneficio-nis">NIS</label>
                                <input id="beneficio-nis" name="beneficio_nis" type="text" inputmode="numeric" placeholder="000.00000.00-0" maxlength="14" x-model="renda.nis" @input="renda.nis = mascararNis($event.target.value)">
                            </div>
                            <div class="field field-tipo">
                                <label for="beneficio-tipo">&nbsp;</label>
                                <select id="beneficio-tipo" name="beneficio_tipo" x-model="renda.tipo">
                                    <option value="" disabled>Tipo de benefício</option>
                                    <option value="Bolsa Família">Bolsa Família</option>
                                    <option value="Cadúnico">Cadúnico</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>
                            <div class="field field-valor">
                                <label for="beneficio-valor">Valor</label>
                                <input id="beneficio-valor" name="beneficio_valor" type="text" inputmode="numeric" placeholder="R$ 0,00" x-model="renda.valor" @input="renda.valor = mascararMoeda($event.target.value)">
                            </div>
                        </div>
                    </section>

                    <div class="cadastro-actions cadastro-actions-split">
                        <a class="button button-secondary" href="{{ route('matricula.crianca') }}">Voltar</a>
                        <button class="button button-primary" type="submit">Próxima</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('responsaveisFamiliaComponent', (inicial) => {
                let proximoId = 1;
                const novoResponsavel = () => ({
                    id: proximoId++, nome: '', parentesco: '', telefone: '', escolaridade: '',
                    salario: '', profissao: '', enderecoComercial: '', religiao: '',
                });
                const novoMembro = () => ({
                    id: proximoId++, nome: '', parentesco: '', dataNascimento: '', renda: '',
                });

                const soDigitos = (valor) => valor.replace(/\D/g, '');

                const responsaveisIniciais = inicial.responsaveis.map((r) => ({ ...novoResponsavel(), ...r }));
                const membrosIniciais = inicial.membros.map((m) => ({ ...novoMembro(), ...m }));

                return {
                    responsaveis: responsaveisIniciais.length ? responsaveisIniciais : [novoResponsavel()],
                    membros: membrosIniciais.length ? membrosIniciais : [novoMembro()],
                    renda: { ...inicial.renda },

                    adicionarResponsavel() { this.responsaveis.push(novoResponsavel()); },
                    adicionarMembro() { this.membros.push(novoMembro()); },
                    remover(lista, indice) { lista.splice(indice, 1); },

                    mascararMoeda(valor) {
                        const digitos = soDigitos(valor);
                        if (!digitos) return '';
                        const numero = (parseInt(digitos, 10) / 100).toLocaleString('pt-BR', {
                            minimumFractionDigits: 2, maximumFractionDigits: 2,
                        });
                        return 'R$ ' + numero;
                    },

                    mascararTelefone(valor) {
                        let d = soDigitos(valor).replace(/^0+/, '');
                        if (d.length > 11 && d.startsWith('55')) d = d.slice(2);
                        d = d.slice(0, 11);
                        if (d.length <= 2) return d ? '(' + d : '';
                        if (d.length <= 6) return `(${d.slice(0, 2)}) ${d.slice(2)}`;
                        if (d.length <= 10) return `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
                        return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
                    },

                    mascararNis(valor) {
                        const d = soDigitos(valor).slice(0, 11);
                        return d
                            .replace(/^(\d{3})(\d)/, '$1.$2')
                            .replace(/^(\d{3})\.(\d{5})(\d)/, '$1.$2.$3')
                            .replace(/^(\d{3}\.\d{5})\.(\d{2})(\d)$/, '$1.$2-$3');
                    },
                };
            });
        });
    </script>
</body>
</html>
