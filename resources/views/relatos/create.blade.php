<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Relato - Recanto Betel</title>

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden text-slate-800">

    <!-- Menu Lateral Fixo -->
    <x-sidebar />

    <!-- Conteúdo Principal -->
    <main class="flex-1 overflow-y-auto p-6 md:p-10 lg:px-14">
        <div class="max-w-4xl mx-auto">

            <!-- Cabeçalho da Página -->
            <header class="mb-8">
                <h1 class="text-2xl md:text-[26px] font-bold text-slate-900 tracking-tight">Novo relato</h1>
                <p class="text-sm text-slate-500 mt-1">Registre uma observação, ocorrência ou acompanhamento sobre uma criança.</p>
            </header>

            <!-- Mensagens de Erro Globais (se houver) -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <div class="font-semibold mb-1">Por favor, corrija os erros abaixo:</div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário -->
            <form action="{{ route('relatos.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Card 1: Identificação -->
                <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
                    <!-- Cabeçalho do Card 1 -->
                    <div class="flex items-center gap-3.5 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50/80 border border-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                            1
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 leading-tight">Identificação</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Quem está registrando e sobre qual criança.</p>
                        </div>
                    </div>

                    <!-- Campos do Card 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Nome do funcionário -->
                        <div>
                            <label for="nome_funcionario" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nome do funcionário
                            </label>
                            <input 
                                type="text" 
                                id="nome_funcionario" 
                                name="nome_funcionario" 
                                value="{{ old('nome_funcionario', auth()->user()?->nome ?? auth()->user()?->name ?? '') }}" 
                                placeholder="Seu nome completo" 
                                class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200/90 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition @error('nome_funcionario') border-red-400 bg-red-50/20 @enderror"
                                required
                            />
                            @error('nome_funcionario')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nome da criança -->
                        <div>
                            <label for="nome_crianca" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nome da criança
                            </label>
                            <input 
                                type="text" 
                                id="nome_crianca" 
                                name="nome_crianca" 
                                list="lista-criancas"
                                value="{{ old('nome_crianca') }}" 
                                placeholder="Ex: Maria Julia de Souza" 
                                autocomplete="off"
                                class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200/90 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition @error('nome_crianca') border-red-400 bg-red-50/20 @enderror"
                                required
                            />
                            <datalist id="lista-criancas">
                                @foreach($criancas as $c)
                                    <option value="{{ $c->nome }}"></option>
                                @endforeach
                            </datalist>
                            @error('nome_crianca')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Data do ocorrido (ocupa metade da largura como no design) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="data_ocorrido" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Data do ocorrido
                            </label>
                            <div class="relative">
                                <input 
                                    type="date" 
                                    id="data_ocorrido" 
                                    name="data_ocorrido" 
                                    value="{{ old('data_ocorrido', date('Y-m-d')) }}" 
                                    placeholder="dd/mm/aa" 
                                    class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200/90 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition @error('data_ocorrido') border-red-400 bg-red-50/20 @enderror"
                                    required
                                />
                            </div>
                            @error('data_ocorrido')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <!-- Card 2: Relato -->
                <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8" x-data="{ texto: @js(old('relato', '')) }">
                    <!-- Cabeçalho do Card 2 -->
                    <div class="flex items-center gap-3.5 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50/80 border border-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                            2
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 leading-tight">Relato</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Descreva o que aconteceu.</p>
                        </div>
                    </div>

                    <!-- Textarea com contador de caracteres -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="relato" class="text-xs font-semibold text-slate-700">
                                Descrição do relato
                            </label>
                            <span class="text-xs text-slate-400 font-medium">
                                <span x-text="texto.length">0</span>/1000
                            </span>
                        </div>
                        <textarea 
                            id="relato" 
                            name="relato" 
                            x-model="texto"
                            maxlength="1000" 
                            rows="6" 
                            placeholder="Descreva o que aconteceu com o máximo de detalher possivel..." 
                            class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200/90 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition resize-y @error('relato') border-red-400 bg-red-50/20 @enderror"
                            required
                        ></textarea>
                        @error('relato')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <!-- Botões de Ação do Rodapé -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a 
                        href="{{ route('relatos.index') }}" 
                        class="px-6 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium text-sm hover:bg-slate-50 transition shadow-sm cursor-pointer"
                    >
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="px-7 py-2.5 rounded-xl bg-[#265ae1] hover:bg-[#1e4dc9] text-white font-medium text-sm transition shadow-sm shadow-blue-500/20 cursor-pointer"
                    >
                        Enviar relato
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
