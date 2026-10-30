<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Relato - Recanto Betel</title>

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden text-slate-800" x-data="{ modalExcluir: false }">

    <!-- Menu Lateral Fixo -->
    <x-sidebar />

    <!-- Conteúdo Principal -->
    <main class="flex-1 overflow-y-auto p-6 md:p-10 lg:px-14">
        <div class="max-w-4xl mx-auto">

            <!-- Cabeçalho da Página com Navegação -->
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                        <a href="{{ route('relatos.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Voltar para relatos
                        </a>
                        <span>/</span>
                        <span>Detalhes</span>
                    </div>
                    <h1 class="text-2xl md:text-[26px] font-bold text-slate-900 tracking-tight">Relato de {{ $relato->nome_crianca }}</h1>
                    <p class="text-sm text-slate-500 mt-1">Registrado por {{ $relato->nome_funcionario }} em {{ $relato->created_at ? $relato->created_at->format('d/m/Y \à\s H:i') : '--' }}.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a 
                        href="{{ route('relatos.edit', $relato->id) }}" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium text-sm transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar relato
                    </a>
                    <button 
                        type="button" 
                        @click="modalExcluir = true" 
                        class="p-2.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 border border-slate-200 transition cursor-pointer"
                        title="Excluir relato"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </header>

            <div class="space-y-6">
                <!-- Card 1: Identificação -->
                <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3.5 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50/80 border border-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                            1
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 leading-tight">Identificação</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Informações da criança e do funcionário responsável.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/60 rounded-xl p-5 border border-slate-100">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Nome da Criança</span>
                            <span class="text-sm font-bold text-slate-800">{{ $relato->nome_crianca }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Nome do Funcionário</span>
                            <span class="text-sm font-bold text-slate-800">{{ $relato->nome_funcionario }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Data do Ocorrido</span>
                            <span class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $relato->data_ocorrido ? $relato->data_ocorrido->format('d/m/Y') : '--' }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Card 2: Relato -->
                <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3.5 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50/80 border border-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                            2
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 leading-tight">Descrição do Relato</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Conteúdo registrado sobre o ocorrido.</p>
                        </div>
                    </div>

                    <div class="bg-slate-50/60 rounded-xl p-6 border border-slate-100 text-sm leading-relaxed text-slate-700 whitespace-pre-wrap">
                        {{ $relato->relato }}
                    </div>
                </section>

                <!-- Rodapé com Botão Voltar -->
                <div class="flex items-center justify-between pt-2">
                    <a 
                        href="{{ route('relatos.index') }}" 
                        class="px-6 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium text-sm hover:bg-slate-50 transition shadow-sm"
                    >
                        ← Voltar para lista
                    </a>
                    <a 
                        href="{{ route('relatos.edit', $relato->id) }}" 
                        class="px-7 py-2.5 rounded-xl bg-[#265ae1] hover:bg-[#1e4dc9] text-white font-medium text-sm transition shadow-sm shadow-blue-500/20"
                    >
                        Editar este relato
                    </a>
                </div>
            </div>
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
                Tem certeza de que deseja remover este relato? Esta ação não pode ser desfeita.
            </p>

            <form action="{{ route('relatos.destroy', $relato->id) }}" method="POST" class="flex items-center justify-end gap-3">
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
