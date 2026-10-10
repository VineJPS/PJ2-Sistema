@php
    use Database\Seeders\CriancaDataSeeder;
    
    // Puxar dados da criança do seeder
    $crianca = CriancaDataSeeder::getCriancaById($criancaId);
    $relatos = $crianca['relatos'] ?? ['relatos_lista' => []];
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/relatoCrianca.css') }}">
@endonce

<div class="conteudoAba" x-data="relatosCriancaComponent({{ json_encode($relatos['relatos_lista'] ?? []) }})">

    <div class="listaRelatos">
        <template x-for="item in listaRelatos" :key="item.relato_id">
            <div class="cardRelato" @click="relatoSelecionado = item">
                <div class="headerRelato">
                    <div class="iconeUsuario"
                        :style="`mask-image: url('{{ asset('assets/person 2.png') }}'); -webkit-mask-image: url('{{ asset('assets/person 2.png') }}');`"
                    ></div>
                    <h3 class="nomeFuncionario" x-text="item.relato_funcionario"></h3>
                </div>
                <p class="textoResumo" x-text="item.relato_texto_curto"></p>
            </div>
        </template>
    </div>

    @include('components.modal.modalRelatosCriancas.modalRelatos')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('relatosCriancaComponent', (dadosIniciais) => ({
            // Dados vindos do backend via rota
            listaRelatos: (dadosIniciais && dadosIniciais.length > 0) ? dadosIniciais : [],

            // Relato atualmente aberto no modal (null = modal fechado)
            relatoSelecionado: null,
        }));
    });
</script>