<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endereço e gastos - Recanto Betel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/cadastroCrianca.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroEnderecoGastos.css') }}">
</head>
@php
    $dados = $dados ?? [];
    $tiposMoradia = ['Própria', 'Cedida', 'Alugada'];
    $moradiaAtual = old('tipo_moradia', $dados['tipo_moradia'] ?? '');
    $campo = fn (string $nome) => old($nome, $dados[$nome] ?? '');
@endphp
<body class="cadastro-body">
    <div class="cadastro-layout">
        <x-sidebar />

        <main class="cadastro-main">
            <div class="cadastro-wrap" x-data="{ moradia: @js($moradiaAtual) }">
                <header class="cadastro-heading">
                    <h1>Endereço e gastos</h1>
                    <p>Residência da família e custos mensais.</p>
                </header>

                @if (session('success'))
                    <div class="aviso-sucesso" role="status">{{ session('success') }}</div>
                @endif

                <form class="cadastro-form" method="POST" action="{{ route('matricula.endereco.salvar') }}" aria-label="Endereço e gastos">
                    @csrf
                    <section class="cadastro-card endereco-card">
                        <div class="cadastro-fields">
                            <div class="field field-half">
                                <label for="rua">Rua</label>
                                <input id="rua" name="rua" type="text" placeholder="Nome da rua" maxlength="255" required value="{{ $campo('rua') }}" @error('rua') aria-invalid="true" @enderror>
                                @error('rua')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field field-half">
                                <label for="numero">Número</label>
                                <input id="numero" name="numero" type="text" placeholder="Nº" maxlength="20" required value="{{ $campo('numero') }}" @error('numero') aria-invalid="true" @enderror>
                                @error('numero')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-half">
                                <label for="bairro">Bairro</label>
                                <input id="bairro" name="bairro" type="text" placeholder="Bairro" maxlength="100" required value="{{ $campo('bairro') }}" @error('bairro') aria-invalid="true" @enderror>
                                @error('bairro')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field field-half">
                                <label for="cidade">Cidade</label>
                                <input id="cidade" name="cidade" type="text" placeholder="Cidade" maxlength="100" required value="{{ $campo('cidade') }}" @error('cidade') aria-invalid="true" @enderror>
                                @error('cidade')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-half">
                                <label for="estado">Estado</label>
                                <input id="estado" name="estado" type="text" placeholder="UF" maxlength="2" pattern="[A-Za-z]{2}" title="Sigla com 2 letras" required value="{{ $campo('estado') }}" style="text-transform: uppercase" @error('estado') aria-invalid="true" @enderror>
                                @error('estado')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field field-half">
                                <label for="ponto-referencia">Ponto de referência <span class="opcional">(opcional)</span></label>
                                <input id="ponto-referencia" name="ponto_referencia" type="text" placeholder="Ex: perto da praça" maxlength="255" value="{{ $campo('ponto_referencia') }}">
                            </div>

                            <div class="field field-full moradia-grupo">
                                <span class="rotulo-grupo" id="rotulo-moradia">Tipo de moradia</span>
                                <div class="moradia-linha">
                                    <div class="opcoes-moradia" role="radiogroup" aria-labelledby="rotulo-moradia">
                                        @foreach ($tiposMoradia as $tipo)
                                            <label class="opcao-moradia" :class="{ 'selecionada': moradia === '{{ $tipo }}' }">
                                                <input type="radio" name="tipo_moradia" value="{{ $tipo }}" required x-model="moradia" @checked($moradiaAtual === $tipo)>
                                                <span class="radio-visual" aria-hidden="true"></span>
                                                {{ $tipo }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="aluguel-campo" x-show="moradia === 'Alugada'" x-cloak>
                                        <input id="valor-aluguel" name="valor_aluguel" type="text" inputmode="numeric" placeholder="R$ 0,00" aria-label="Valor do aluguel" data-moeda :required="moradia === 'Alugada'" value="{{ $campo('valor_aluguel') }}" @error('valor_aluguel') aria-invalid="true" @enderror>
                                    </div>
                                </div>
                                @error('tipo_moradia')<small class="field-error">{{ $message }}</small>@enderror
                                @error('valor_aluguel')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-half">
                                <label for="agua">Água</label>
                                <input id="agua" name="agua" type="text" inputmode="numeric" placeholder="R$ 0,00" data-moeda required value="{{ $campo('agua') }}" @error('agua') aria-invalid="true" @enderror>
                                @error('agua')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field field-half">
                                <label for="energia">Energia</label>
                                <input id="energia" name="energia" type="text" inputmode="numeric" placeholder="R$ 0,00" data-moeda required value="{{ $campo('energia') }}" @error('energia') aria-invalid="true" @enderror>
                                @error('energia')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-half">
                                <label for="alimentacao">Alimentação</label>
                                <input id="alimentacao" name="alimentacao" type="text" inputmode="numeric" placeholder="R$ 0,00" data-moeda required value="{{ $campo('alimentacao') }}" @error('alimentacao') aria-invalid="true" @enderror>
                                @error('alimentacao')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field field-half">
                                <label for="gas">Gás</label>
                                <input id="gas" name="gas" type="text" inputmode="numeric" placeholder="R$ 0,00" data-moeda required value="{{ $campo('gas') }}" @error('gas') aria-invalid="true" @enderror>
                                @error('gas')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="cadastro-actions cadastro-actions-split">
                        <a class="button button-secondary" href="{{ route('matricula.responsaveis') }}">Voltar</a>
                        <button class="button button-primary" type="submit">Próxima</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const formatarMoeda = (valor) => {
            const digitos = valor.replace(/\D/g, '');
            if (!digitos) return '';
            return 'R$ ' + (parseInt(digitos, 10) / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2, maximumFractionDigits: 2,
            });
        };

        document.querySelectorAll('[data-moeda]').forEach((input) => {
            input.addEventListener('input', () => { input.value = formatarMoeda(input.value); });
        });
    </script>
</body>
</html>
