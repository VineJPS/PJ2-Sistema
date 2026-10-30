<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatos - Recanto Betel</title>

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden text-slate-800" x-data="{ modalExcluir: false, relatoParaExcluir: null }">

    <!-- Menu Lateral Fixo -->
    <x-sidebar />

    <!-- Conteúdo Principal -->
    <main class="flex-1 overflow-y-auto p-6 md:p-10 lg:px-14">
        <div class="max-w-6xl mx-auto">

            <!-- Alerta de Sucesso -->
            @if (session('success') || session('sucesso'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    role="status"
                    class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') ?? session('sucesso') }}</span>
                    </div>
                    <button type="button" @click="show = false" class="text-green-500 hover:text-green-700 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Cabeçalho da Página -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl md:text-[26px] font-bold text-slate-900 tracking-tight">Relatos</h1>
                    <p class="text-sm text-slate-500 mt-1">Histórico e acompanhamento de ocorrências e observações das crianças.</p>
                </div>
                <a 
                    href="{{ route('relatos.create') }}" 
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#265ae1] hover:bg-[#1e4dc9] text-white font-medium text-sm transition shadow-sm shadow-blue-500/20 cursor-pointer shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Novo relato
                </a>
            </div>

            <!-- Barra de Filtros e Busca -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('relatos.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative flex-1 w-full">
                        <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}" 
                            placeholder="Buscar por criança, funcionário ou conteúdo do relato..." 
                            class="w-full pl-10 pr-4 py-2.5 bg-[#f8fafc] border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition"
                        />
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition cursor-pointer"
                        >
                            Filtrar
                        </button>
                        @if(!empty($search))
                            <a 
                                href="{{ route('relatos.index') }}" 
                                class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium rounded-xl transition text-center"
                            >
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Listagem de Relatos -->
            @if($relatos->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Nenhum relato encontrado</h3>
                    <p class="text-xs text-slate-400 mb-6 max-w-sm mx-auto">
                        @if(!empty($search))
                            Nenhum resultado corresponde à sua pesquisa "{{ $search }}". Tente buscar por outros termos.
                        @else
                            Ainda não há relatos cadastrados no sistema. Comece registrando o primeiro!
                        @endif
                    </p>
                    <a 
                        href="{{ route('relatos.create') }}" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#265ae1] hover:bg-[#1e4dc9] text-white font-medium text-sm transition shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Criar primeiro relato
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        @foreach($relatos as $item)
                            <article class="p-5 md:p-6 hover:bg-slate-50/70 transition flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <!-- Cabeçalho do Card: Nome da Criança, Funcionário e Data -->
                                    <div class="flex flex-wrap items-center gap-3 mb-2.5">
                                        <div class="w-9 h-9 rounded-full bg-blue-100/80 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($item->nome_crianca, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h2 class="text-base font-bold text-slate-900 leading-tight">
                                                <a href="{{ route('relatos.show', $item->id) }}" class="hover:text-blue-600 transition">
                                                    {{ $item->nome_crianca }}
                                                </a>
                                            </h2>
                                            <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5">
                                                <span>Por <strong class="text-slate-600 font-medium">{{ $item->nome_funcionario }}</strong></span>
                                                <span>•</span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $item->data_ocorrido ? $item->data_ocorrido->format('d/m/Y') : '--' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Descrição do Relato (truncada para até 220 caracteres) -->
                                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed mt-2 pl-12">
                                        {{ $item->relato }}
                                    </p>
                                </div>

                                <!-- Ações -->
                                <div class="flex items-center gap-2 shrink-0 md:self-center pl-12 md:pl-0">
                                    <a 
                                        href="{{ route('relatos.show', $item->id) }}" 
                                        class="p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition"
                                        title="Ver detalhes"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a 
                                        href="{{ route('relatos.edit', $item->id) }}" 
                                        class="p-2 rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition"
                                        title="Editar relato"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button 
                                        type="button" 
                                        @click="modalExcluir = true; relatoParaExcluir = { id: {{ $item->id }}, crianca: '{{ addslashes($item->nome_crianca) }}' }" 
                                        class="p-2 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition cursor-pointer"
                                        title="Excluir relato"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Paginação -->
                    @if($relatos->hasPages())
                        <div class="p-4 border-t border-slate-100">
                            {{ $relatos->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <!-- Modal de Confirmação de Exclusão -->
    <div 
        x-show="modalExcluir" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
    >
        <div 
            @click.away="modalExcluir = false" 
            class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100"
        >
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 text-center mb-1">Excluir relato</h3>
            <p class="text-xs text-slate-500 text-center mb-6">
                Tem certeza de que deseja remover o relato da criança <strong x-text="relatoParaExcluir ? relatoParaExcluir.crianca : ''"></strong>? Esta ação não pode ser desfeita.
            </p>

            <form :action="relatoParaExcluir ? `/relatos/${relatoParaExcluir.id}` : '#'" method="POST" class="flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button 
                    type="button" 
                    @click="modalExcluir = false" 
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium text-sm hover:bg-slate-50 transition cursor-pointer"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition shadow-sm cursor-pointer"
                >
                    Sim, excluir
                </button>
            </form>
        </div>
    </div>

</body>
</html>
