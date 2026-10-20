@php
    $crianca = \App\Models\Crianca::dadosTela($criancaId);
    $responsaveis = $crianca['responsaveis'] ?? [];
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/responsaveisCrianca.css') }}">
@endonce

<div class="conteudoAba" x-data="responsaveisCriancaComponent({{ json_encode($responsaveis) }}, {{ (int) $criancaId }})">
    <p x-show="erro" x-text="erro" style="color:#dc2626;font-size:0.85rem;margin-bottom:8px;"></p>

    <template x-for="(resp, index) in listaResponsaveis" :key="index">
        <div class="cardInfo">
            <div class="cardHeader">
                <h2 class="cardTitulo" x-text="resp.parentesco"></h2>

                <template x-if="editingIndex === index">
                    <div class="acoesEdicao">
                        <button type="button" class="btnSalvar" @click="salvarEdicao(index)">Salvar</button>
                        <button type="button" class="btnX" @click="cancelarEdicao()">X</button>
                    </div>
                </template>
                <template x-if="editingIndex !== index">
                    <button type="button" class="btnEditar" @click="iniciarEdicao(index)">
                        <img src="{{ asset('assets/edit.png') }}" alt="Editar" class="iconeLapis" /> Editar
                    </button>
                </template>
            </div>

            <div class="gridDuasColunas">
                <div class="campoGrupo">
                    <label class="labelCampo">Nome completo</label>
                    <template x-if="editingIndex === index">
                        <input type="text" x-model="tempData.nome" class="inputCampo" />
                    </template>
                    <template x-if="editingIndex !== index">
                        <p class="valorCampo" x-text="resp.nome"></p>
                    </template>
                </div>

                <div class="campoGrupo">
                    <label class="labelCampo">Telefone</label>
                    <template x-if="editingIndex === index">
                        <input type="text" x-model="tempData.telefone" class="inputCampo" />
                    </template>
                    <template x-if="editingIndex !== index">
                        <p class="valorCampo" x-text="resp.telefone"></p>
                    </template>
                </div>
            </div>

            <div class="campoLinhaInteira">
                <label class="labelCampo">Endereço residencial</label>
                <p class="valorCampo" x-text="resp.enderecoResidencial"></p>
            </div>

            <div class="campoLinhaInteira">
                <label class="labelCampo">Endereço comercial</label>
                <template x-if="editingIndex === index">
                    <input type="text" x-model="tempData.enderecoComercial" placeholder="Não informado" class="inputCampo" />
                </template>
                <template x-if="editingIndex !== index">
                    <p class="valorCampo" x-text="resp.enderecoComercial || 'Não informado'"></p>
                </template>
            </div>
        </div>
    </template>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('responsaveisCriancaComponent', (dadosIniciais, criancaId) => ({
            criancaId,
            erro: '',
            listaResponsaveis: dadosIniciais || [],

            // Índice do card em edição (null = nenhum)
            editingIndex: null,

            // Cópia temporária para armazenar as alterações do card em edição
            tempData: {},

            iniciarEdicao(index) {
                this.editingIndex = index;
                this.tempData = JSON.parse(JSON.stringify(this.listaResponsaveis[index]));
            },

            cancelarEdicao() {
                this.editingIndex = null;
                this.tempData = {};
            },

            async salvarEdicao(index) {
                this.erro = '';
                const resp = this.listaResponsaveis[index];
                const resposta = await fetch(`/crianca/${this.criancaId}/responsaveis/${resp.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({
                        nome: this.tempData.nome,
                        telefone: this.tempData.telefone,
                        enderecoComercial: this.tempData.enderecoComercial || null,
                    }),
                });
                const json = await resposta.json().catch(() => ({}));
                if (!resposta.ok) {
                    this.erro = json.errors ? Object.values(json.errors)[0][0] : (json.message || 'Não foi possível salvar.');
                    return;
                }
                this.listaResponsaveis[index] = { ...resp, ...this.tempData, ...(json.responsavel || {}) };
                this.editingIndex = null;
                this.tempData = {};
            }
        }));
    });
</script>