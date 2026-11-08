<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rematrícula – {{ $crianca->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/rematricula.css') }}">
</head>
@php
    $periodos = ['Matutino', 'Vespertino', 'Integral'];
    $turmas = [
        'Turma 1' => 'Turma 1 (6 a 7 anos)',
        'Turma 2' => 'Turma 2 (8 a 9 anos)',
        'Turma 3' => 'Turma 3 (10 a 12 anos)',
    ];
    $anoAtual = (int) now()->format('Y');
@endphp
<body class="rematricula-body">
    <div class="rematricula-layout">
        <x-sidebar />

        <main class="rematricula-main">
            <div class="rematricula-wrap">

                <header class="rematricula-heading">
                    <h1>Rematrícula</h1>
                    <p>Confirme os dados escolares para o novo ano letivo.</p>
                </header>

                <form
                    class="rematricula-form"
                    method="POST"
                    action="{{ route('crianca.rematricula.salvar', $crianca->id) }}"
                    aria-label="Rematrícula"
                >
                    @csrf
                    @method('PATCH')

                    <section class="rematricula-card">
                        <div class="rematricula-fields">

                            {{-- Escola atual --}}
                            <div class="field field-full">
                                <label for="escola">Escola atual</label>
                                <input
                                    id="escola"
                                    name="escola"
                                    type="text"
                                    placeholder="Nome da instituição"
                                    maxlength="255"
                                    required
                                    value="{{ old('escola', $crianca->escola ?? '') }}"
                                    @error('escola') aria-invalid="true" @enderror
                                >
                                @error('escola')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Período --}}
                            <div class="field field-half">
                                <label for="periodo">Período</label>
                                @php $periodoAtual = old('periodo', $crianca->periodo ?? ''); @endphp
                                <select
                                    id="periodo"
                                    name="periodo"
                                    required
                                    @error('periodo') aria-invalid="true" @enderror
                                >
                                    <option value="" disabled @selected($periodoAtual === '')>Selecione o período</option>
                                    @foreach ($periodos as $periodo)
                                        <option value="{{ $periodo }}" @selected($periodoAtual === $periodo)>{{ $periodo }}</option>
                                    @endforeach
                                </select>
                                @error('periodo')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Série --}}
                            <div class="field field-half">
                                <label for="serie">Série</label>
                                <input
                                    id="serie"
                                    name="serie"
                                    type="text"
                                    placeholder="Ex: 5º ano"
                                    maxlength="50"
                                    required
                                    value="{{ old('serie', $crianca->serie ?? '') }}"
                                    @error('serie') aria-invalid="true" @enderror
                                >
                                @error('serie')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Turma Betel --}}
                            <div class="field field-half">
                                <label for="turma-betel">Turma Betel</label>
                                @php $turmaAtual = old('turma_betel', $crianca->turma_betel ?? ''); @endphp
                                <select
                                    id="turma-betel"
                                    name="turma_betel"
                                    required
                                    @error('turma_betel') aria-invalid="true" @enderror
                                >
                                    <option value="" disabled @selected($turmaAtual === '')>Selecione a turma</option>
                                    @foreach ($turmas as $valor => $rotulo)
                                        <option value="{{ $valor }}" @selected($turmaAtual === $valor)>{{ $rotulo }}</option>
                                    @endforeach
                                </select>
                                @error('turma_betel')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Ano letivo --}}
                            <div class="field field-half">
                                <label for="ano-letivo">Ano letivo</label>
                                <input
                                    id="ano-letivo"
                                    name="ano_letivo"
                                    type="number"
                                    placeholder="{{ $anoAtual + 1 }}"
                                    min="2000"
                                    max="2100"
                                    required
                                    value="{{ old('ano_letivo', $anoAtual + 1) }}"
                                    @error('ano_letivo') aria-invalid="true" @enderror
                                >
                                @error('ano_letivo')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </section>

                    {{-- Rodapé com botões --}}
                    <div class="rematricula-footer">
                        <a href="{{ route('crianca.menu', $crianca->id) }}" class="btn-voltar">
                            Voltar
                        </a>
                        <button type="submit" class="btn-proxima">
                            Próxima
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</body>
</html>
