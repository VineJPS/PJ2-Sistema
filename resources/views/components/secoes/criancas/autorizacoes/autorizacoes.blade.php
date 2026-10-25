@php
    $crianca = app(\App\Services\CriancaTelaService::class)->dados($criancaId);
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
                    <button type="button" class="badgePermissao" :class="item.autorizado ? 'badgeAutorizado' : 'badgeNaoAutorizado'"
                        title="Clique para alterar" @click="alternar(item)">
                        <span x-text="item.autorizado ? 'Autorizado' : 'Não autorizado'"></span>
                    </button>
                </div>
                <p class="descricaoAutorizacao" x-text="item.descricao"></p>
            </div>
        </template>
    </div>

    <div class="secaoGrupo">
        <div class="headerGrupo">
            <h2 class="tituloGrupo">Quem pode buscar</h2>
            <div class="wrapperBotoesAdicionar">
                <button type="button" class="btnAdicionar" :disabled="saiSozinho" :title="saiSozinho ? 'Desative a saída sozinho(a) para adicionar autorizados' : ''" @click="abrirForm()">
                    + Novo Autorizado
                </button>
            </div>
        </div>

        @include('components.modal.modalAutorizacoes.novoAutorizado')

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

    @include('components.modal.modalAutorizacoes.removerAutorizado')

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
            erroForm: '',

            get saiSozinho() {
                const p = this.permissoes.find(x => x.id === 'sai_sozinho');
                return !!(p && p.autorizado);
            },
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
                this.erroForm = '';
                if (!this.novo.nome.trim() || !this.novo.parentesco.trim()) {
                    this.erroForm = 'Preencha nome e parentesco.';
                    return;
                }
                if (!/^\([1-9]\d\) (?:9\d{4}|[2-5]\d{3})-\d{4}$/.test(this.novo.telefone)) {
                    this.erroForm = 'Informe um telefone válido, ex: (15) 91234-5678.';
                    return;
                }
                const r = await this.enviar('POST', 'autorizacoes/pessoas', this.novo);
                if (!r) {
                    this.erroForm = this.erro;
                    this.erro = '';
                    return;
                }
                this.pessoas.push(r);
                this.formAberto = false;
            },

            abrirForm() {
                this.novo = { nome: '', parentesco: '', telefone: '' };
                this.erroForm = '';
                this.formAberto = true;
            },

            mascararTelefone(valor) {
                let d = valor.replace(/\D/g, '').replace(/^0+/, '');
                if (d.length > 11 && d.startsWith('55')) d = d.slice(2);
                d = d.slice(0, 11);
                if (d.length > 2 && d[2] !== '9') d = d.slice(0, 10);
                if (d.length <= 2) return d.length ? `(${d}` : '';
                if (d.length <= 6) return `(${d.slice(0, 2)}) ${d.slice(2)}`;
                if (d.length <= 10) return `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
                return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
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
