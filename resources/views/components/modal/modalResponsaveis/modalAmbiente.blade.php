@php
    $ambiente = $ambiente ?? (\App\Models\Crianca::dadosTela($criancaId)['ambiente'] ?? []);

    $campo = function (string $expr, string $label, array $op = []) {
        $tipo = $op['tipo'] ?? 'text';
        $mascara = $op['mascara'] ?? null;
        $max = isset($op['max']) ? ' maxlength="' . $op['max'] . '"' : '';
        $ev = $mascara ? "@input=\"{$expr} = {$mascara}(\$event.target.value); \$event.target.value = {$expr}\"" : "@input=\"{$expr} = \$event.target.value\"";
        $texto = $tipo === 'date' ? "fmtData({$expr})" : "({$expr} || 'Não informado')";
        $prefixo = ($op['moeda'] ?? false) ? "({$expr} ? 'R$ ' + {$expr} : 'Não informado')" : $texto;
        return '<div class="ambCampo"><label>' . e($label) . '</label>'
            . '<p x-show="!editando" x-text="' . $prefixo . '"></p>'
            . '<input x-show="editando" x-cloak type="' . $tipo . '" class="ambInput"' . $max . ' :value="' . $expr . '" ' . $ev . '></div>';
    };
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/responsaveisCrianca.css') }}">
@endonce

<div x-data="ambienteCriancaComponent({{ json_encode($ambiente) }}, {{ (int) $criancaId }})">
    <button type="button" class="btnAmbiente" @click="abrirAmbiente()" aria-label="Mais informações" title="Mais informações">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 11v5M12 8h.01"/></svg>
    </button>

    <div x-show="ambienteAberto" x-cloak style="display:none;" class="ambOverlay" @keydown.escape.window="fecharAmbiente()">
        <div @click.away="fecharAmbiente()" class="ambBox">
            <button type="button" class="ambFechar" @click="fecharAmbiente()" aria-label="Fechar">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="ambHeader">
                <h3 class="ambTitulo">Dados do ambiente</h3>
                <p class="ambSub" x-text="editando ? 'Edite as informações e salve.' : 'Informações complementares da família.'"></p>
            </div>

            <div class="ambCorpo">
                <div class="ambSecao">
                    <h4 class="ambSecaoTitulo">Responsáveis</h4>
                    <template x-for="(r, i) in atual.responsaveis" :key="r.id">
                        <div>
                            <p class="ambSubtitulo" x-text="`${r.parentesco}: ${r.nome}`"></p>
                            <div class="ambGrid">
                                {!! $campo('r.profissao', 'Profissão', ['max' => 150]) !!}
                                {!! $campo('r.religiao', 'Religião', ['max' => 100]) !!}
                                {!! $campo('r.escolaridade', 'Escolaridade', ['max' => 100]) !!}
                                {!! $campo('r.salario', 'Salário', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                            </div>
                        </div>
                    </template>
                </div>

                <div class="ambSecao">
                    <h4 class="ambSecaoTitulo">Composição familiar</h4>
                    <p class="ambVazio" x-show="!atual.membros.length">Nenhum membro informado.</p>
                    <template x-for="(m, i) in atual.membros" :key="i">
                        <div class="ambMembro" :class="{ ambMembroLeitura: !editando }">
                            <div x-show="!editando" x-text="m.nome"></div>
                            <div x-show="!editando" x-text="m.parentesco"></div>
                            <div x-show="!editando" x-text="fmtData(m.dataNascimento) || '—'"></div>
                            <div x-show="!editando" x-text="m.renda ? 'R$ ' + m.renda : '—'"></div>

                            <input x-show="editando" x-cloak class="ambInput" placeholder="Nome" maxlength="150" :value="m.nome" @input="m.nome = $event.target.value">
                            <input x-show="editando" x-cloak class="ambInput" placeholder="Parentesco" maxlength="100" :value="m.parentesco" @input="m.parentesco = $event.target.value">
                            <input x-show="editando" x-cloak type="date" class="ambInput" :value="m.dataNascimento" @input="m.dataNascimento = $event.target.value">
                            <input x-show="editando" x-cloak class="ambInput" placeholder="Renda" maxlength="14" :value="m.renda" @input="m.renda = mascararMoeda($event.target.value); $event.target.value = m.renda">
                            <button type="button" x-show="editando" x-cloak @click="form.membros.splice(i, 1)" aria-label="Remover membro">
                                <img src="{{ asset('assets/trash.png') }}" alt="Remover" class="iconeLixeira" style="width:16px;height:16px;">
                            </button>
                        </div>
                    </template>
                    <button type="button" class="ambAdicionar" x-show="editando" x-cloak
                        @click="form.membros.push({ nome: '', parentesco: '', dataNascimento: '', renda: '' })">+ Adicionar membro</button>
                </div>

                <div class="ambSecao">
                    <h4 class="ambSecaoTitulo">Renda e benefícios</h4>
                    <div class="ambGrid">
                        {!! $campo('atual.renda.total', 'Renda total', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                        {!! $campo('atual.renda.perCapita', 'Renda per capita', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                        <div class="ambCampo">
                            <label>Recebe benefício</label>
                            <p x-show="!editando" x-text="atual.renda.recebeBeneficio ? 'Sim' : 'Não'"></p>
                            <select x-show="editando" x-cloak class="ambInput" :value="atual.renda.recebeBeneficio ? '1' : '0'"
                                @change="atual.renda.recebeBeneficio = $event.target.value === '1'">
                                <option value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>
                    </div>
                    <div class="ambGrid" x-show="atual.renda.recebeBeneficio" style="margin-top:10px;">
                        {!! $campo('atual.renda.nis', 'NIS', ['max' => 20]) !!}
                        {!! $campo('atual.renda.tipo', 'Tipo de benefício', ['max' => 100]) !!}
                        {!! $campo('atual.renda.valor', 'Valor do benefício', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                    </div>
                </div>

                <div class="ambSecao">
                    <h4 class="ambSecaoTitulo">Endereço</h4>
                    <div class="ambGrid">
                        {!! $campo('atual.endereco.rua', 'Rua', ['max' => 150]) !!}
                        {!! $campo('atual.endereco.numero', 'Número', ['max' => 20]) !!}
                        {!! $campo('atual.endereco.bairro', 'Bairro', ['max' => 100]) !!}
                        {!! $campo('atual.endereco.cidade', 'Cidade', ['max' => 100]) !!}
                        {!! $campo('atual.endereco.estado', 'Estado (UF)', ['max' => 2]) !!}
                        {!! $campo('atual.endereco.pontoReferencia', 'Ponto de referência', ['max' => 255]) !!}
                        <div class="ambCampo">
                            <label>Tipo de moradia</label>
                            <p x-show="!editando" x-text="atual.endereco.moradia || 'Não informado'"></p>
                            <select x-show="editando" x-cloak class="ambInput" :value="atual.endereco.moradia"
                                @change="atual.endereco.moradia = $event.target.value">
                                <option value="Própria">Própria</option>
                                <option value="Cedida">Cedida</option>
                                <option value="Alugada">Alugada</option>
                            </select>
                        </div>
                        <div x-show="atual.endereco.moradia === 'Alugada'">
                            {!! $campo('atual.endereco.valorAluguel', 'Valor do aluguel', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                        </div>
                    </div>
                </div>

                <div class="ambSecao">
                    <h4 class="ambSecaoTitulo">Gastos mensais</h4>
                    <div class="ambGrid">
                        {!! $campo('atual.gastos.agua', 'Água', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                        {!! $campo('atual.gastos.energia', 'Energia', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                        {!! $campo('atual.gastos.gas', 'Gás', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                        {!! $campo('atual.gastos.alimentacao', 'Alimentação', ['mascara' => 'mascararMoeda', 'moeda' => true, 'max' => 14]) !!}
                    </div>
                </div>
            </div>

            <p class="ambErro" x-show="erroAmbiente" x-text="erroAmbiente"></p>

            <div class="ambAcoes">
                <template x-if="!editando">
                    <button type="button" class="ambBtnSec" @click="fecharAmbiente()">Fechar</button>
                </template>
                <template x-if="!editando">
                    <button type="button" class="ambBtnPri" @click="iniciarEdicaoAmbiente()">Editar</button>
                </template>
                <template x-if="editando">
                    <button type="button" class="ambBtnSec" @click="cancelarEdicaoAmbiente()">Cancelar</button>
                </template>
                <template x-if="editando">
                    <button type="button" class="ambBtnPri" @click="salvarAmbiente()">Salvar</button>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ambienteCriancaComponent', (ambienteInicial, criancaId) => ({
            criancaId,
            ambiente: ambienteInicial,
            ambienteAberto: false,
            editando: false,
            form: null,
            erroAmbiente: '',

            get atual() {
                return this.editando ? this.form : this.ambiente;
            },

            abrirAmbiente() {
                this.erroAmbiente = '';
                this.editando = false;
                this.ambienteAberto = true;
            },

            fecharAmbiente() {
                this.ambienteAberto = false;
                this.editando = false;
                this.form = null;
            },

            iniciarEdicaoAmbiente() {
                this.erroAmbiente = '';
                this.form = JSON.parse(JSON.stringify(this.ambiente));
                if (!this.form.endereco.moradia) this.form.endereco.moradia = 'Própria';
                this.editando = true;
            },

            cancelarEdicaoAmbiente() {
                this.editando = false;
                this.form = null;
                this.erroAmbiente = '';
            },

            mascararMoeda(valor) {
                const d = String(valor).replace(/\D/g, '').replace(/^0+/, '').slice(0, 11);
                if (!d) return '';
                return (parseInt(d, 10) / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            fmtData(iso) {
                if (!iso) return '';
                const [a, m, d] = iso.split('-');
                return `${d}/${m}/${a}`;
            },

            async salvarAmbiente() {
                this.erroAmbiente = '';
                const resposta = await fetch(`/crianca/${this.criancaId}/ambiente`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify(this.form),
                });
                const json = await resposta.json().catch(() => ({}));
                if (!resposta.ok) {
                    this.erroAmbiente = json.errors ? Object.values(json.errors)[0][0] : (json.message || 'Não foi possível salvar.');
                    return;
                }
                this.ambiente = json.ambiente;
                this.$dispatch('ambiente-atualizado', {
                    enderecos: Object.fromEntries((json.dados.responsaveis || []).map(r => [r.id, r.enderecoResidencial])),
                });
                this.editando = false;
                this.form = null;
            },
        }));
    });
</script>
