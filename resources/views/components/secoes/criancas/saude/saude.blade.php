@php

    // Puxar dados da criança do seeder
    $crianca = app(\App\Services\CriancaTelaService::class)->dados($criancaId);
    $dadosSaude = $crianca['saude'] ?? [];
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/saudeCrianca.css') }}">
@endonce

<div class="conteudoAba pgSaude" x-data="saudeCriancaComponent({{ json_encode($dadosSaude) }})">

    {{-- Banner de Alerta Crítico (Condicional) --}}
    @if(!empty($dadosSaude['tem_alergia_grave']) && !empty($dadosSaude['alerta_remedio']))
        <div class="bannerAlertaRemedio">
            <img src="{{ asset('assets/alert-triangle.png') }}" alt="Alerta" class="iconeAlerta">
            <div>
                <h3 class="tituloAlertaRemedio">{{ $dadosSaude['alerta_remedio'] }}</h3>
                @if(!empty($dadosSaude['alerta_remedio_instrucao']))
                    <p class="subtituloAlertaRemedio">{{ $dadosSaude['alerta_remedio_instrucao'] }}</p>
                @endif
            </div>
        </div>
    @endif

    <div class="cardInfo" style="padding: 20px;">

        {{-- Queixa principal --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Queixa principal / evolução</h2>
            <template x-if="isEditing">
                <textarea x-model="form.queixa_principal" class="inputCampo" rows="3"></textarea>
            </template>
            <template x-if="!isEditing">
                <p class="textoValor" x-text="form.queixa_principal || '--'"></p>
            </template>
        </div>

        {{-- Questionário de Saúde --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Questionário de saúde</h2>

            {{-- Campos Sim/Não --}}
            <div class="gridTrisecao">
                <template x-for="campo in camposQuestionario" :key="campo.key">
                    <div>
                        <label class="labelCampo" x-text="campo.label"></label>
                        <template x-if="isEditing">
                            <select x-model="form[campo.key]" class="inputCampo">
                                <option value="">--</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </template>
                        <template x-if="!isEditing">
                            <p class="valorCampo" x-text="form[campo.key] || '--'"></p>
                        </template>
                    </div>
                </template>
            </div>

            <div class="gridDuasColunas">
                <div>
                    <label class="labelCampo">Uso de medicação</label>
                    <template x-if="isEditing">
                        <input type="text" x-model="form.uso_medicacao" class="inputCampo" />
                    </template>
                    <template x-if="!isEditing">
                        <p class="valorCampo" x-text="form.uso_medicacao || '--'"></p>
                    </template>
                </div>

                <div>
                    <label class="labelCampo">Médico assistente</label>
                    <template x-if="isEditing">
                        <div class="linhaInputs">
                            <input type="text" x-model="form.medico_assistente" class="inputCampo" placeholder="Nome" />
                            <input type="text" x-model="form.medico_telefone" class="inputCampo" placeholder="Telefone" />
                        </div>
                    </template>
                    <template x-if="!isEditing">
                        <p class="valorCampo" x-text="(form.medico_assistente || '--') + ' ' + (form.medico_telefone || '')"></p>
                    </template>
                </div>
            </div>
        </div>

        {{-- Cirurgias (lista de textos editáveis) --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Cirurgias</h2>

            <template x-if="!isEditing">
                <div class="listaCirurgias">
                    <template x-for="(item, i) in form.cirurgias" :key="i">
                        <span class="itemCirurgia" x-text="item"></span>
                    </template>
                </div>
            </template>

            <template x-if="isEditing">
                <div class="listaCirurgiasEdicao">
                    <template x-for="(item, i) in form.cirurgias" :key="i">
                        <div class="linhaInputs">
                            <input type="text" x-model="form.cirurgias[i]" class="inputCampo" />
                            <button type="button" class="btnCancelar" @click="removerCirurgia(i)">X</button>
                        </div>
                    </template>
                    <button type="button" class="btnEditarOutline" @click="adicionarCirurgia()">+ Adicionar cirurgia</button>
                </div>
            </template>
        </div>

        {{-- Doenças específicas (clique para marcar/desmarcar no modo edição) --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Doenças específicas</h2>
            <div class="wrapperTags">
                <template x-for="(d, i) in form.doencas_especificas" :key="i">
                    <span class="tagDoenca"
                          :class="{ 'tagSelecionada': d.selecionado, 'tagEditavel': isEditing }"
                          @click="isEditing && (d.selecionado = !d.selecionado)"
                          x-text="d.nome"></span>
                </template>
            </div>
        </div>

        {{-- Cicatrização, anestesia e hemorragia (Sim/Não com estilo de alerta dinâmico) --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Cicatrização, anestesia e hemorragia</h2>
            <div class="gridTrisecao">
                <template x-for="campo in camposAlerta" :key="campo.key">
                    <div>
                        <label :class="isSim(form[campo.key]) ? 'labelCampoAlerta' : 'labelCampo'" x-text="campo.label"></label>
                        <template x-if="isEditing">
                            <select x-model="form[campo.key]" class="inputCampo">
                                <option value="">--</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </template>
                        <template x-if="!isEditing">
                            <p :class="isSim(form[campo.key]) ? 'valorCampoAlerta' : 'valorCampo'" x-text="form[campo.key] || '--'"></p>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Hábitos --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Hábitos</h2>
            <template x-if="isEditing">
                <textarea x-model="form.habitos" class="inputCampo" rows="2"></textarea>
            </template>
            <template x-if="!isEditing">
                <p class="textoValor" x-text="form.habitos || '--'"></p>
            </template>
        </div>

        {{-- Antecedentes familiares --}}
        <div class="secaoBloco">
            <h2 class="secaoTitulo">Antecedentes familiares</h2>
            <template x-if="isEditing">
                <textarea x-model="form.antecedentes_familiares" class="inputCampo" rows="2"></textarea>
            </template>
            <template x-if="!isEditing">
                <p class="textoValor" x-text="form.antecedentes_familiares || '--'"></p>
            </template>
        </div>

        {{-- Outras informações --}}
        <div class="secaoBlocoSemBorda">
            <h2 class="secaoTitulo">Outras informações</h2>
            <template x-if="isEditing">
                <textarea x-model="form.outras_informacoes" class="inputCampo" rows="3"></textarea>
            </template>
            <template x-if="!isEditing">
                <p class="textoCinza" x-text="form.outras_informacoes || '--'"></p>
            </template>
        </div>

        {{-- Ações --}}
        <div class="containerAcoes">
            <template x-if="isEditing">
                <div class="acoesEdicao">
                    <button type="button" class="btnSalvar" @click="salvar()">Salvar</button>
                    <button type="button" class="btnCancelar" @click="cancelar()">Cancelar</button>
                </div>
            </template>
            <template x-if="!isEditing">
                <button type="button" class="btnEditarOutline" @click="isEditing = true">
                    <img src="{{ asset('assets/edit.png') }}" alt="Editar" class="iconeLapis"> Editar ficha
                </button>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('saudeCriancaComponent', (dadosIniciais) => ({
            isEditing: false,

            // Campos Sim/Não do questionário
            camposQuestionario: [
                { key: 'sofre_doenca', label: 'Sofre de doença' },
                { key: 'em_tratamento', label: 'Em tratamento' },
                { key: 'gravidez', label: 'Gravidez' },
            ],

            // Campos Sim/Não que ficam em alerta (vermelho) quando = "Sim"
            camposAlerta: [
                { key: 'problema_cicatrizacao', label: 'Problema de cicatrização' },
                { key: 'alerta_anestesia', label: 'Problemas com anestesia' },
                { key: 'hemorragia', label: 'Hemorragia' },
            ],

            form: {},
            backup: {},

            init() {
                // Garante valores padrão para arrays (evita erro no x-for)
                this.form = {
                    ...dadosIniciais,
                    cirurgias: [...(dadosIniciais.cirurgias || [])],
                    doencas_especificas: JSON.parse(JSON.stringify(dadosIniciais.doencas_especificas || [])),
                };
                this.backup = JSON.parse(JSON.stringify(this.form));
            },

            isSim(valor) {
                return !!valor && String(valor).trim().toLowerCase() === 'sim';
            },

            adicionarCirurgia() {
                this.form.cirurgias.push('');
            },

            removerCirurgia(i) {
                this.form.cirurgias.splice(i, 1);
            },

            salvar() {
                // Remove cirurgias vazias antes de guardar
                this.form.cirurgias = this.form.cirurgias.filter(c => c && c.trim() !== '');
                this.backup = JSON.parse(JSON.stringify(this.form));
                this.isEditing = false;
                // TODO: enviar this.form para o backend (fetch/axios) quando existir a rota
            },

            cancelar() {
                this.form = JSON.parse(JSON.stringify(this.backup));
                this.isEditing = false;
            }
        }));
    });
</script>