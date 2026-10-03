@php
    use Database\Seeders\CriancaDataSeeder;
    
    // Puxar dados da criança do seeder
    $crianca = CriancaDataSeeder::getCriancaById($criancaId);
    $responsaveis = $crianca['responsaveis'] ?? [];
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/responsaveisCrianca.css') }}">
@endonce

<div class="conteudoAba" x-data="responsaveisCriancaComponent({{ json_encode($responsaveis) }})">

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
                <template x-if="editingIndex === index">
                    <input type="text" x-model="tempData.enderecoResidencial" class="inputCampo" />
                </template>
                <template x-if="editingIndex !== index">
                    <p class="valorCampo" x-text="resp.enderecoResidencial"></p>
                </template>
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
        Alpine.data('responsaveisCriancaComponent', (dadosIniciais) => ({
            // Dados vindos direto do seeder via Blade
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

            salvarEdicao(index) {
                this.listaResponsaveis[index] = JSON.parse(JSON.stringify(this.tempData));
                this.editingIndex = null;
                this.tempData = {};

                // Aqui você pode enviar uma requisição fetch/axios para o backend, ex:
                // fetch(`/responsaveis/${this.listaResponsaveis[index].id}`, {
                //     method: 'PUT',
                //     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                //     body: JSON.stringify(this.listaResponsaveis[index])
                // });
            }
        }));
    });
</script>