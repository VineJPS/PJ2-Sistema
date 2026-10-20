@php
    $crianca = \App\Models\Crianca::dadosTela($criancaId);
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/matriculaCrianca.css') }}">
@endonce

<div class="conteudoAba" x-data="matriculaCriancaComponent({{ json_encode($crianca ?? []) }}, {{ (int) $criancaId }})">

    <!-- Card de Dados Pessoais -->
    <div class="cardInfo">
        <div class="cardHeader">
            <div>
                <h2 class="cardTitulo">Dados Pessoais</h2>
                <p class="cardSubtitulo">Documentos e identificação civil</p>
            </div>

            <template x-if="isEditingPessoais">
                <div class="acoesEdicao">
                    <button type="button" class="btnSalvar" @click="salvarPessoais()">Salvar</button>
                    <button type="button" class="btnCancelar" @click="cancelarPessoais()">X</button>
                </div>
            </template>
            <template x-if="!isEditingPessoais">
                <button type="button" class="btnEditar" @click="isEditingPessoais = true">
                    <img src="{{ asset('assets/edit.png') }}" alt="Editar" class="iconeLapis" /> Editar
                </button>
            </template>
        </div>

        <div class="gridTrisecao">
            <div class="campoGrupo">
                <label class="labelCampo">Data de Nascimento</label>
                <template x-if="isEditingPessoais">
                    <input type="text" x-model="formData.dataNascimento" class="inputCampo" />
                </template>
                <template x-if="!isEditingPessoais">
                    <p class="valorCampo" x-text="formData.dataNascimento"></p>
                </template>
            </div>

            <div class="campoGrupo">
                <label class="labelCampo">CPF</label>
                <template x-if="isEditingPessoais">
                    <input type="text" x-model="formData.cpf" class="inputCampo" />
                </template>
                <template x-if="!isEditingPessoais">
                    <p class="valorCampo" x-text="formData.cpf"></p>
                </template>
            </div>

            <div class="campoGrupo">
                <label class="labelCampo">RG</label>
                <template x-if="isEditingPessoais">
                    <input type="text" x-model="formData.rg" class="inputCampo" />
                </template>
                <template x-if="!isEditingPessoais">
                    <p class="valorCampo" x-text="formData.rg"></p>
                </template>
            </div>
        </div>

        <div class="campoLinhaInteira">
            <label class="labelCampo">Endereço residencial</label>
            <template x-if="isEditingPessoais">
                <div class="gridTrisecao">
                    <input type="text" x-model="formData.enderecoCampos.rua" placeholder="Rua" class="inputCampo" />
                    <input type="text" x-model="formData.enderecoCampos.numero" placeholder="Número" class="inputCampo" />
                    <input type="text" x-model="formData.enderecoCampos.bairro" placeholder="Bairro" class="inputCampo" />
                    <input type="text" x-model="formData.enderecoCampos.cidade" placeholder="Cidade" class="inputCampo" />
                    <input type="text" x-model="formData.enderecoCampos.estado" placeholder="UF" maxlength="2" class="inputCampo" />
                </div>
            </template>
            <p x-show="erro" x-text="erro" style="color:#dc2626;font-size:0.8rem;margin-top:6px;"></p>
            <template x-if="!isEditingPessoais">
                <p class="valorCampo" x-text="formData.endereco"></p>
            </template>
        </div>
    </div>

    <!-- Card de Matrícula Betel (aparece somente quando status da criança = Inativo) -->
    <!-- <template x-if="statusCrianca === 'inativo'"> -->
        <div class="cardInfo">
            <div class="cardHeader">
                <div>
                    <h2 class="cardTitulo">Matricula Betel</h2>
                    <p class="cardSubtitulo">Matrícula em nossa rede de ensino</p>
                </div>

                <template x-if="isEditingBetel">
                    <div class="acoesEdicao">
                        <button type="button" class="btnSalvar" @click="salvarBetel()">Salvar</button>
                        <button type="button" class="btnCancelar" @click="cancelarBetel()">X</button>
                    </div>
                </template>
                <template x-if="!isEditingBetel">
                    <button type="button" class="btnEditar" @click="isEditingBetel = true">
                        <img src="{{ asset('assets/edit.png') }}" alt="Editar" class="iconeLapis" /> Editar
                    </button>
                </template>
            </div>

            <div class="gridTrisecaoSemBordaInferior">
                <div class="campoGrupo">
                    <label class="labelCampo">Escola</label>
                    <p class="valorCampo" x-text="formData.escolaBetel"></p>
                </div>

                <div class="campoGrupo">
                    <label class="labelCampo">Período</label>
                    <p class="valorCampo" x-text="formData.periodo"></p>
                </div>

                <div class="campoGrupo">
                    <label class="labelCampo">Classe</label>
                    <template x-if="isEditingBetel">
                        <select x-model="formData.classeBetel" class="inputCampo">
                            <option value="">Selecione</option>
                            <option>Turma 1</option>
                            <option>Turma 2</option>
                            <option>Turma 3</option>
                        </select>
                    </template>
                    <template x-if="!isEditingBetel">
                        <p class="valorCampo" x-text="formData.classeBetel"></p>
                    </template>
                </div>
            </div>
        </div>
    <!-- </template> -->

    <!-- Card de Informações Escolares -->
    <div class="cardInfo">
        <div class="cardHeader">
            <div>
                <h2 class="cardTitulo">Informações escolares</h2>
                <p class="cardSubtitulo">Matrícula na rede de ensino</p>
            </div>

            <template x-if="isEditingEscolares">
                <div class="acoesEdicao">
                    <button type="button" class="btnSalvar" @click="salvarEscolares()">Salvar</button>
                    <button type="button" class="btnCancelar" @click="cancelarEscolares()">X</button>
                </div>
            </template>
            <template x-if="!isEditingEscolares">
                <button type="button" class="btnEditar" @click="isEditingEscolares = true">
                    <img src="{{ asset('assets/edit.png') }}" alt="Editar" class="iconeLapis" /> Editar
                </button>
            </template>
        </div>

        <div class="gridTrisecaoSemBordaInferior">
            <div class="campoGrupo">
                <label class="labelCampo">Escola</label>
                <template x-if="isEditingEscolares">
                    <input type="text" x-model="formData.escola" class="inputCampo" />
                </template>
                <template x-if="!isEditingEscolares">
                    <p class="valorCampo" x-text="formData.escola"></p>
                </template>
            </div>

            <div class="campoGrupo">
                <label class="labelCampo">Período</label>
                <template x-if="isEditingEscolares">
                    <select x-model="formData.periodo" class="inputCampo">
                        <option value="">Selecione</option>
                        <option>Matutino</option>
                        <option>Vespertino</option>
                        <option>Integral</option>
                    </select>
                </template>
                <template x-if="!isEditingEscolares">
                    <p class="valorCampo" x-text="formData.periodo"></p>
                </template>
            </div>

            <div class="campoGrupo">
                <label class="labelCampo">Série</label>
                <template x-if="isEditingEscolares">
                    <input type="text" x-model="formData.serie" class="inputCampo" />
                </template>
                <template x-if="!isEditingEscolares">
                    <p class="valorCampo" x-text="formData.serie"></p>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('matriculaCriancaComponent', (dadosIniciais, criancaId) => ({
            isEditingPessoais: false,
            isEditingEscolares: false,
            isEditingBetel: false,
            erro: '',

            formData: {
                dataNascimento: dadosIniciais.dataNascimento,
                cpf: dadosIniciais.cpf,
                rg: dadosIniciais.rg,
                endereco: dadosIniciais.endereco,
                enderecoCampos: { ...dadosIniciais.enderecoCampos },
                escola: dadosIniciais.escola,
                periodo: dadosIniciais.periodo,
                serie: dadosIniciais.serie,
                escolaBetel: dadosIniciais.escolaBetel,
                periodoBetel: dadosIniciais.periodoBetel,
                classeBetel: dadosIniciais.classeBetel
            },

            backupData: {},

            init() {
                this.backupData = JSON.parse(JSON.stringify(this.formData));
            },

            async salvar(fechar) {
                this.erro = '';
                const payload = { ...this.formData };
                // Campos vazios devem chegar como nulos, nunca como texto vazio
                Object.keys(payload).forEach(k => { if (payload[k] === '') payload[k] = null; });
                const resposta = await fetch(`/crianca/${criancaId}/dados`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify(payload),
                });
                const json = await resposta.json().catch(() => ({}));
                if (!resposta.ok) {
                    this.erro = json.errors ? Object.values(json.errors)[0][0] : (json.message || 'Não foi possível salvar.');
                    return;
                }
                const d = json.dados;
                Object.keys(this.formData).forEach(k => {
                    if (d[k] !== undefined) this.formData[k] = k === 'enderecoCampos' ? { ...d[k] } : d[k];
                });
                this.backupData = JSON.parse(JSON.stringify(this.formData));
                this.$dispatch('crianca-atualizada', { idade: d.idade });
                fechar();
            },

            salvarPessoais() {
                this.salvar(() => { this.isEditingPessoais = false; });
            },

            cancelarPessoais() {
                this.formData.dataNascimento = this.backupData.dataNascimento;
                this.formData.cpf = this.backupData.cpf;
                this.formData.rg = this.backupData.rg;
                this.formData.endereco = this.backupData.endereco;
                this.formData.enderecoCampos = { ...this.backupData.enderecoCampos };
                this.erro = '';
                this.isEditingPessoais = false;
            },

            salvarEscolares() {
                this.salvar(() => { this.isEditingEscolares = false; });
            },

            cancelarEscolares() {
                this.formData.escola = this.backupData.escola;
                this.formData.periodo = this.backupData.periodo;
                this.formData.serie = this.backupData.serie;
                this.erro = '';
                this.isEditingEscolares = false;
            },

            salvarBetel() {
                this.salvar(() => { this.isEditingBetel = false; });
            },

            cancelarBetel() {
                this.formData.escolaBetel = this.backupData.escolaBetel;
                this.formData.periodoBetel = this.backupData.periodoBetel;
                this.formData.classeBetel = this.backupData.classeBetel;
                this.isEditingBetel = false;
            }
        }));
    });
</script>