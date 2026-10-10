@php
    use Database\Seeders\CriancaDataSeeder;
    
    // Puxar dados da criança do seeder
    $crianca = CriancaDataSeeder::getCriancaById($criancaId);
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/matriculaCrianca.css') }}">
@endonce

<div class="conteudoAba" x-data="matriculaCriancaComponent({{ json_encode($crianca ?? []) }})">

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
                <input type="text" x-model="formData.endereco" class="inputCampo" />
            </template>
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
                    <template x-if="isEditingBetel">
                        <input type="text" x-model="formData.escolaBetel" class="inputCampo" />
                    </template>
                    <template x-if="!isEditingBetel">
                        <p class="valorCampo" x-text="formData.escolaBetel"></p>
                    </template>
                </div>

                <div class="campoGrupo">
                    <label class="labelCampo">Período</label>
                    <template x-if="isEditingBetel">
                        <input type="text" x-model="formData.periodoBetel" class="inputCampo" />
                    </template>
                    <template x-if="!isEditingBetel">
                        <p class="valorCampo" x-text="formData.periodoBetel"></p>
                    </template>
                </div>

                <div class="campoGrupo">
                    <label class="labelCampo">Classe</label>
                    <template x-if="isEditingBetel">
                        <input type="text" x-model="formData.classeBetel" class="inputCampo" />
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
                    <input type="text" x-model="formData.periodo" class="inputCampo" />
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
        Alpine.data('matriculaCriancaComponent', (dadosIniciais) => ({
            isEditingPessoais: false,
            isEditingEscolares: false,
            isEditingBetel: false,

            // Status normalizado em minúsculo: 'ativo' | 'alérgico' | 'inativo'
            statusCrianca: (dadosIniciais.status || '').toLowerCase(),

            // Dados vindos direto do seeder via Blade
            formData: {
                dataNascimento: dadosIniciais.dataNascimento,
                cpf: dadosIniciais.cpf,
                rg: dadosIniciais.rg,
                endereco: dadosIniciais.endereco,
                escola: dadosIniciais.escola,
                periodo: dadosIniciais.periodo,
                serie: dadosIniciais.serie,

                // Campos da Matrícula Betel
                escolaBetel: dadosIniciais.escolaBetel,
                periodoBetel: dadosIniciais.periodoBetel,
                classeBetel: dadosIniciais.classeBetel
            },
            
            backupData: {},

            init() {
                this.backupData = JSON.parse(JSON.stringify(this.formData));
            },

            salvarPessoais() {
                this.isEditingPessoais = false;
                this.backupData = JSON.parse(JSON.stringify(this.formData));
            },

            cancelarPessoais() {
                this.formData.dataNascimento = this.backupData.dataNascimento;
                this.formData.cpf = this.backupData.cpf;
                this.formData.rg = this.backupData.rg;
                this.formData.endereco = this.backupData.endereco;
                this.isEditingPessoais = false;
            },

            salvarEscolares() {
                this.isEditingEscolares = false;
                this.backupData = JSON.parse(JSON.stringify(this.formData));
            },

            cancelarEscolares() {
                this.formData.escola = this.backupData.escola;
                this.formData.periodo = this.backupData.periodo;
                this.formData.serie = this.backupData.serie;
                this.isEditingEscolares = false;
            },

            salvarBetel() {
                this.isEditingBetel = false;
                this.backupData = JSON.parse(JSON.stringify(this.formData));
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