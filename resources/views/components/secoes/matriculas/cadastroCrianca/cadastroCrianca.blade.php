<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de criança - Recanto Betel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/cadastroCrianca.css') }}">
</head>
@php
    $dados = $dados ?? [];
    $periodos = ['Matutino', 'Vespertino', 'Integral'];
    $turmas = [
        'Turma 1' => 'Turma 1 (6 a 7 anos)',
        'Turma 2' => 'Turma 2 (8 a 9 anos)',
        'Turma 3' => 'Turma 3 (10 a 12 anos)',
    ];
@endphp
<body class="cadastro-body">
    <div class="cadastro-layout">
        <x-sidebar />

        <main class="cadastro-main">
            <div class="cadastro-wrap">
                <header class="cadastro-heading">
                    <h1>Dados da criança</h1>
                    <p>Informações pessoais e escolares.</p>
                </header>

                <form class="cadastro-form" method="POST" action="{{ route('matricula.crianca.salvar') }}" enctype="multipart/form-data" aria-label="Dados da criança">
                    @csrf
                    <section class="cadastro-card">
                        @php $temFoto = (bool) session('matricula.crianca.foto_path'); @endphp
                        <div class="foto-linha">
                        <label class="foto-upload" for="foto-crianca">
                            <span class="foto-icon @if($temFoto) foto-icon-ativa @endif" id="foto-icon">
                                <svg viewBox="0 0 24 24" fill="none" id="foto-svg" @if($temFoto) style="display:none" @endif aria-hidden="true">
                                    <path d="M4.5 8.5h3l1.4-2h6.2l1.4 2h3A1.5 1.5 0 0 1 21 10v8a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18v-8a1.5 1.5 0 0 1 1.5-1.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                    <circle cx="12" cy="13.5" r="3" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                                <img id="foto-preview" class="foto-preview" alt="Foto da criança"
                                    @if($temFoto) src="{{ route('matricula.crianca.foto') }}?v={{ time() }}" @else style="display:none" @endif>
                            </span>
                            <span class="foto-copy">
                                <strong id="foto-nome">Foto da criança</strong>
                                <small>PNG ou JPG, até 5MB</small>
                            </span>
                            <input id="foto-crianca" name="foto" type="file" accept="image/png,image/jpeg">
                        </label>
                        <input type="hidden" name="remover_foto" id="remover-foto" value="0">
                        <button type="button" id="foto-remover" class="foto-remover" aria-label="Remover foto" title="Remover foto" @if(!$temFoto) disabled @endif>
                            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true">
                                <path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-12M9 7V4h6v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        </div>
                        @error('foto')
                            <small class="field-error foto-error">{{ $message }}</small>
                        @enderror

                        <div class="cadastro-fields">
                            <div class="field field-full">
                                <label for="nome">Nome completo</label>
                                <input id="nome" name="nome" type="text" placeholder="Nome completo" autocomplete="name" maxlength="255" required value="{{ old('nome', $dados['nome'] ?? '') }}" @error('nome') aria-invalid="true" @enderror>
                                @error('nome')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field">
                                <label for="data-nascimento">Data de nascimento</label>
                                <input id="data-nascimento" name="data_nascimento" type="date" max="{{ now()->toDateString() }}" required value="{{ old('data_nascimento', $dados['data_nascimento'] ?? '') }}" @error('data_nascimento') aria-invalid="true" @enderror>
                                @error('data_nascimento')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field">
                                <label for="cpf">CPF</label>
                                <input id="cpf" name="cpf" type="text" inputmode="numeric" placeholder="000.000.000-00" maxlength="14" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="000.000.000-00" value="{{ old('cpf', $dados['cpf'] ?? '') }}" @error('cpf') aria-invalid="true" @enderror>
                                @error('cpf')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field">
                                <label for="rg">RG</label>
                                <input id="rg" name="rg" type="text" placeholder="00.000.000-0" maxlength="20" value="{{ old('rg', $dados['rg'] ?? '') }}" @error('rg') aria-invalid="true" @enderror>
                                @error('rg')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-full">
                                <label for="escola">Escola atual</label>
                                <input id="escola" name="escola" type="text" placeholder="Nome da instituição" maxlength="255" required value="{{ old('escola', $dados['escola'] ?? '') }}" @error('escola') aria-invalid="true" @enderror>
                                @error('escola')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-half">
                                <label for="periodo">Período</label>
                                @php $periodoAtual = old('periodo', $dados['periodo'] ?? ''); @endphp
                                <select id="periodo" name="periodo" required @error('periodo') aria-invalid="true" @enderror>
                                    <option value="" disabled @selected($periodoAtual === '')>Selecione o período</option>
                                    @foreach ($periodos as $periodo)
                                        <option value="{{ $periodo }}" @selected($periodoAtual === $periodo)>{{ $periodo }}</option>
                                    @endforeach
                                </select>
                                @error('periodo')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field field-half">
                                <label for="serie">Série</label>
                                <input id="serie" name="serie" type="text" placeholder="Ex: 5º ano" maxlength="50" required value="{{ old('serie', $dados['serie'] ?? '') }}" @error('serie') aria-invalid="true" @enderror>
                                @error('serie')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="field field-half">
                                <label for="turma-betel">Turma Betel</label>
                                @php $turmaAtual = old('turma_betel', $dados['turma_betel'] ?? ''); @endphp
                                <select id="turma-betel" name="turma_betel" required @error('turma_betel') aria-invalid="true" @enderror>
                                    <option value="" disabled @selected($turmaAtual === '')>Selecione a turma</option>
                                    @foreach ($turmas as $valor => $rotulo)
                                        <option value="{{ $valor }}" @selected($turmaAtual === $valor)>{{ $rotulo }}</option>
                                    @endforeach
                                </select>
                                @error('turma_betel')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="cadastro-actions">
                        <a class="button button-secondary" href="{{ route('home') }}">Cancelar</a>
                        <button class="button button-primary" type="submit">Avançar</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('cpf').addEventListener('input', function () {
            const d = this.value.replace(/\D/g, '').slice(0, 11);
            this.value = d
                .replace(/^(\d{3})(\d)/, '$1.$2')
                .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1-$2');
        });

        const fotoInput = document.getElementById('foto-crianca');
        const fotoPreview = document.getElementById('foto-preview');
        const fotoSvg = document.getElementById('foto-svg');
        const fotoIcon = document.getElementById('foto-icon');
        const fotoRemover = document.getElementById('foto-remover');

        fotoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            document.getElementById('foto-nome').textContent = file.name;
            document.getElementById('remover-foto').value = '0';
            fotoPreview.src = URL.createObjectURL(file);
            fotoPreview.style.display = 'block';
            fotoSvg.style.display = 'none';
            fotoIcon.classList.add('foto-icon-ativa');
            fotoRemover.disabled = false;
        });

        fotoRemover.addEventListener('click', function () {
            fotoInput.value = '';
            document.getElementById('foto-nome').textContent = 'Foto da criança';
            document.getElementById('remover-foto').value = '1';
            fotoPreview.removeAttribute('src');
            fotoPreview.style.display = 'none';
            fotoSvg.style.display = '';
            fotoIcon.classList.remove('foto-icon-ativa');
            this.disabled = true;
        });
    </script>
</body>
</html>
