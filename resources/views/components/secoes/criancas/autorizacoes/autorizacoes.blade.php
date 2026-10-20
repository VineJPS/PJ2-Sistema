@php
    $crianca = \App\Models\Crianca::dadosTela($criancaId);
    $dadosAutorizacao = $crianca['autorizacoes'] ?? [
        'autorizacao_permissoes' => [],
        'autorizacao_pessoas_buscar' => [],
        'autorizacao_assinatura' => [],
    ];
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/autorizacaoCrianca.css') }}">
@endonce

<div class="conteudoAba" x-data="autorizacoesCriancaComponent({{ json_encode($dadosAutorizacao) }}, {{ (int) $criancaId }})">
    <p x-show="erro" x-text="erro" style="color:#dc2626;font-size:0.85rem;margin-bottom:8px;"></p>

    <div class="secaoAutorizacoes">
        <template x-for="item in permissoes" :key="item.id">
            <div class="cardAutorizacao">
                <div class="headerAutorizacao">
                    <h3 class="tituloAutorizacao" x-text="item.titulo"></h3>
                    <span :class="item.autorizado ? 'badgeAutorizado' : 'badgeNaoAutorizado'"
                        style="cursor:pointer" title="Clique para alterar"
                        @click="alternar(item)"
                        x-text="item.autorizado ? 'Autorizado' : 'Não autorizado'"></span>
                </div>
                <p class="descricaoAutorizacao" x-text="item.descricao"></p>
            </div>
        </template>
    </div>

    <div class="secaoGrupo">
        <div class="headerGrupo">
            <h2 class="tituloGrupo">Quem pode buscar</h2>
            <div class="wrapperBotoesAdicionar">
                <button type="button" class="btnAdicionar" @click="formAberto = !formAberto">
                    + Novo Autorizado
                </button>
            </div>
        </div>

        <div class="cardPessoa" x-show="formAberto" x-cloak style="display:none; gap:8px; flex-wrap:wrap;">
            <input type="text" x-model="novo.nome" placeholder="Nome" class="inputCampo" />
            <input type="text" x-model="novo.parentesco" placeholder="Parentesco" class="inputCampo" />
            <input type="text" x-model="novo.telefone" placeholder="(00) 00000-0000" class="inputCampo" />
            <button type="button" class="btnSalvar" @click="adicionar()">Salvar</button>
            <button type="button" class="btnCancelar" @click="formAberto = false">X</button>
        </div>

        <div class="listaPessoas">
            <template x-for="pessoa in pessoas" :key="pessoa.id">
                <div class="cardPessoa">
                    <div>
                        <h4 class="nomePessoa" x-text="`${pessoa.parentesco}: ${pessoa.nome}`"></h4>
                        <p class="telefonePessoa" x-text="pessoa.telefone"></p>
                    </div>

                    <button type="button" class="btnExcluir" aria-label="Excluir pessoa autorizada"
                        @click="removendo = pessoa">
                        <img src="{{ asset('assets/trash.png') }}" alt="Excluir" class="iconeLixeira">
                    </button>
                </div>
            </template>
        </div>
    </div>

    <div x-show="removendo" x-cloak style="display:none;" class="modalOverlay"
        @keydown.escape.window="removendo = null">
        <div @click.away="removendo = null" class="modalBox">
            <button type="button" @click="removendo = null" class="modalFechar" aria-label="Fechar">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 class="modalTitulo">Remover autorizado</h3>
            <p class="modalTexto">
                Essa pessoa deixa de poder buscar a criança (não pode ser desfeita).
            </p>
            <div class="modalAfetado">
                <p class="modalAfetadoNome" x-text="removendo ? removendo.nome : ''"></p>
                <p class="modalAfetadoSub" x-text="removendo ? removendo.parentesco : ''"></p>
            </div>
            <div class="modalAcoes">
                <button type="button" @click="removendo = null" class="modalBtnCancelar">Cancelar</button>
                <button type="button" @click="excluir(removendo)" class="modalBtnRemover">Remover</button>
            </div>
        </div>
    </div>

    <div class="secaoGrupo">
        <h2 class="tituloGrupo">
            Assinatura do responsável
        </h2>
        <div class="cardAssinatura">
            <div>
                <h4 class="nomeAssinante" x-text="assinatura.nome || '--'"></h4>
                <p class="dataAssinatura">
                    Assinado em <span x-text="assinatura.dataAssinatura || '--'"></span>
                </p>
            </div>
            <input type="file" accept="image/png,image/jpeg" x-ref="arquivo" class="hidden" style="display:none"
                @change="enviarAssinatura($event)">
            <button type="button" class="btnAssinarNovamente" @click="$refs.arquivo.click()">
                Assinar novamente
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('autorizacoesCriancaComponent', (dados, criancaId) => ({
            erro: '',
            formAberto: false,
            removendo: null,
            permissoes: dados.autorizacao_permissoes || [],
            pessoas: dados.autorizacao_pessoas_buscar || [],
            assinatura: dados.autorizacao_assinatura || {},
            novo: { nome: '', parentesco: '', telefone: '' },

            async enviar(metodo, caminho, corpo) {
                this.erro = '';
                const headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) };
                if (!(corpo instanceof FormData)) headers['Content-Type'] = 'application/json';
                const resposta = await fetch(`/crianca/${criancaId}/${caminho}`, {
                    method: metodo,
                    headers,
                    body: corpo instanceof FormData ? corpo : (corpo ? JSON.stringify(corpo) : null),
                });
                const json = await resposta.json().catch(() => ({}));
                if (!resposta.ok) {
                    this.erro = json.errors ? Object.values(json.errors)[0][0] : (json.message || 'Não foi possível concluir a ação.');
                    return null;
                }
                return json;
            },

            async alternar(item) {
                const novoValor = !item.autorizado;
                const r = await this.enviar('PATCH', 'autorizacoes/permissoes', { campo: item.id, valor: novoValor });
                if (r) item.autorizado = novoValor;
            },

            async adicionar() {
                const r = await this.enviar('POST', 'autorizacoes/pessoas', this.novo);
                if (!r) return;
                this.pessoas.push(r);
                this.novo = { nome: '', parentesco: '', telefone: '' };
                this.formAberto = false;
            },

            async excluir(pessoa) {
                this.removendo = null;
                const r = await this.enviar('DELETE', `autorizacoes/pessoas/${pessoa.id}`);
                if (r) this.pessoas = this.pessoas.filter(p => p.id !== pessoa.id);
            },

            async enviarAssinatura(evento) {
                const arquivo = evento.target.files[0];
                if (!arquivo) return;
                const form = new FormData();
                form.append('assinatura', arquivo);
                const r = await this.enviar('POST', 'assinatura', form);
                if (r) this.assinatura.dataAssinatura = r.dataAssinatura;
                evento.target.value = '';
            },
        }));
    });
</script>
