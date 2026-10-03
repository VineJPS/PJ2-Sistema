@php
    use Database\Seeders\CriancaDataSeeder;
    
    // Puxar dados da criança do seeder
    $crianca = CriancaDataSeeder::getCriancaById($criancaId);
    $dadosAutorizacao = $crianca['autorizacoes'] ?? [
        'autorizacao_permissoes' => [],
        'autorizacao_pessoas_buscar' => [],
        'autorizacao_assinatura' => [],
    ];
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/autorizacaoCrianca.css') }}">
@endonce

<div class="conteudoAba">
    <div class="secaoAutorizacoes">

        @foreach ($dadosAutorizacao['autorizacao_permissoes'] ?? [] as $item)

            <div class="cardAutorizacao">

                <div class="headerAutorizacao">

                    <h3 class="tituloAutorizacao">
                        {{ $item['titulo'] ?? '' }}
                    </h3>

                    @if ($item['autorizado'] ?? false)
                        <span class="badgeAutorizado">
                            Autorizado
                        </span>
                    @else
                        <span class="badgeNaoAutorizado">
                            Não autorizado
                        </span>
                    @endif

                </div>

                <p class="descricaoAutorizacao">
                    {{ $item['descricao'] ?? '' }}
                </p>

            </div>

        @endforeach

    </div>

    <div class="secaoGrupo">

        <div class="headerGrupo">

            <h2 class="tituloGrupo">
                Quem pode buscar
            </h2>

            <div class="wrapperBotoesAdicionar">

                <button
                    type="button"
                    class="btnAdicionar"
                >
                    + Novo Autorizado
                </button>

            </div>

        </div>


        <div class="listaPessoas">

            @foreach ($dadosAutorizacao['autorizacao_pessoas_buscar'] ?? [] as $pessoa)

                <div class="cardPessoa">
                    <div>
                        <h4 class="nomePessoa">
                            {{ ($pessoa['parentesco'] ?? '') }}: {{ ($pessoa['nome'] ?? '') }}
                        </h4>
                        <p class="telefonePessoa">
                            {{ $pessoa['telefone'] ?? '' }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btnExcluir"
                        aria-label="Excluir pessoa autorizada"
                    >
                        <img
                            src="{{ asset('assets/trash.png') }}"
                            alt="Excluir"
                            class="iconeLixeira"
                        >
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="secaoGrupo">
        <h2 class="tituloGrupo">
            Assinatura do responsável
        </h2>
        <div class="cardAssinatura">
            <div>
                <h4 class="nomeAssinante">
                    {{ $dadosAutorizacao['autorizacao_assinatura']['nome'] ?? '--' }}
                </h4>
                <p class="dataAssinatura">
                    Assinado em
                    {{ $dadosAutorizacao['autorizacao_assinatura']['dataAssinatura'] ?? '--' }}
                </p>
            </div>
            <button
                type="button"
                class="btnAssinarNovamente"
            >
                Assinar novamente
            </button>

        </div>
    </div>
</div>

