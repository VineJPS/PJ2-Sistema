<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Gerenciamento de Usuários</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden">

    <!-- Incluindo o Componente Sidebar Fixa (com "Administrador" ativo) -->
    <x-sidebar />

    <!-- Conteúdo Principal com Estado Central de Modais -->
    <main 
        x-data="{ 
            modalAberta: null, 
            usuarioSelecionado: null,
            editForm: {
                id: '',
                nome: '',
                email: '',
                telefone: '',
                role: 'colaborador',
                status: 'ativo'
            },
            abrirEditar(user) {
                this.usuarioSelecionado = user;
                this.editForm = {
                    id: user.id,
                    nome: user.name || user.nome || '',
                    email: user.email || '',
                    telefone: user.phone || user.telefone || '',
                    role: user.role || 'colaborador',
                    status: user.status || 'ativo'
                };
                this.modalAberta = 'editarUsuario';
            }
        }" 
        class="flex-1 p-8 md:p-12 overflow-y-auto"
    >
        
        <!-- Feedback de Notificação de Sucesso -->
        @if(session('success') || session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') ?? session('sucesso') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <!-- Feedback de Erros -->
        @if(session('error') || $errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl shadow-sm">
                <div class="flex items-center gap-2 font-semibold text-red-800 mb-1">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') ?? 'Ocorreram erros no processamento:' }}</span>
                </div>
                @if($errors->any())
                    <ul class="list-disc list-inside space-y-1 text-red-600 text-xs pl-2">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <!-- Cabeçalho da Página e Botão de Ação -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#1e293b] mb-1">Usuários</h1>
                <p class="text-[#64748b] text-sm md:text-base">Gerencie quem tem acesso ao painel e o que cada pessoa pode fazer.</p>
            </div>
            <button 
                type="button" 
                @click="modalAberta = 'convidar'" 
                class="inline-flex items-center justify-center gap-2 bg-[#1e40af] hover:bg-blue-800 text-white font-semibold px-5 py-3 rounded-xl shadow-sm transition-colors text-sm cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Convidar usuário
            </button>
        </div>

        <!-- Barra de Ferramentas (Busca e Filtros de Abas) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <!-- Campo de Busca -->
            <form method="GET" action="{{ route('admin.usuarios.index') }}" class="relative w-full md:w-96">
                <input type="hidden" name="tab" value="{{ $tab ?? 'todos' }}">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Buscar por nome ou e-mail..." 
                    class="w-full bg-white border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors shadow-sm"
                />
            </form>

            <!-- Abas de Filtro -->
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200/60 self-start md:self-auto">
                <a 
                    href="{{ route('admin.usuarios.index', ['tab' => 'todos', 'search' => $search ?? '']) }}" 
                    class="px-4 py-1.5 rounded-lg text-xs transition-all {{ ($tab ?? 'todos') === 'todos' ? 'font-semibold bg-white text-slate-800 shadow-sm' : 'font-medium text-slate-600 hover:text-slate-800' }}"
                >
                    Todos
                </a>
                <a 
                    href="{{ route('admin.usuarios.index', ['tab' => 'administradores', 'search' => $search ?? '']) }}" 
                    class="px-4 py-1.5 rounded-lg text-xs transition-all {{ ($tab ?? '') === 'administradores' ? 'font-semibold bg-white text-slate-800 shadow-sm' : 'font-medium text-slate-600 hover:text-slate-800' }}"
                >
                    Administradores
                </a>
                <a 
                    href="{{ route('admin.usuarios.index', ['tab' => 'colaboradores', 'search' => $search ?? '']) }}" 
                    class="px-4 py-1.5 rounded-lg text-xs transition-all {{ ($tab ?? '') === 'colaboradores' ? 'font-semibold bg-white text-slate-800 shadow-sm' : 'font-medium text-slate-600 hover:text-slate-800' }}"
                >
                    Colaboradores
                </a>
            </div>
        </div>

        <!-- Tabela de Usuários -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50/50">
                            <th class="py-4 px-6">Usuário</th>
                            <th class="py-4 px-6">Função</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Desde</th>
                            <th class="py-4 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        
                        @forelse($usuarios as $user)
                            @php
                                $roleNormalized = strtolower($user->role ?? ($user->nivel ? $user->nivel->nome : 'colaborador'));
                                $isAdmin = $roleNormalized === 'administrador';
                                $statusNormalized = strtolower($user->status ?? 'ativo');
                                $userInitials = strtoupper(substr($user->name ?? $user->nome ?? 'U', 0, 2));
                                
                                // Cores de Avatar
                                $avatarBg = match($user->id % 5) {
                                    1 => 'bg-blue-600',
                                    2 => 'bg-emerald-600',
                                    3 => 'bg-amber-600',
                                    4 => 'bg-purple-600',
                                    default => 'bg-indigo-600',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full {{ $avatarBg }} flex items-center justify-center text-white font-bold text-xs shrink-0">
                                            {{ $userInitials }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $user->name ?? $user->nome ?? 'Usuário' }}</p>
                                            <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($isAdmin)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            Colaborador
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($statusNormalized === 'ativo')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Ativo
                                        </span>
                                    @elseif($statusNormalized === 'pendente')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pendente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-500 text-xs">
                                    @if($statusNormalized === 'pendente')
                                        Convite enviado
                                    @elseif($user->created_at)
                                        {{ $user->created_at->translatedFormat('M Y') }}
                                    @else
                                        Mar 2022
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right relative" x-data="{ menuOpen: false }">
                                    <div class="flex items-center justify-end gap-1">
                                        <!-- Botão Ação Rápida: Editar -->
                                        <button 
                                            type="button" 
                                            @click="abrirEditar({{ json_encode($user) }})" 
                                            title="Editar usuário"
                                            class="text-slate-400 hover:text-blue-600 p-1.5 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <!-- Botão Três Pontos (Menu de Ações) -->
                                        <button 
                                            type="button" 
                                            @click="menuOpen = !menuOpen" 
                                            title="Mais opções"
                                            class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                        </button>
                                    </div>

                                    <!-- Dropdown de Ações por Linha -->
                                    <div 
                                        x-show="menuOpen" 
                                        @click.away="menuOpen = false" 
                                        x-cloak
                                        class="absolute right-6 top-12 z-20 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 text-left text-xs font-medium text-slate-700 divide-y divide-slate-100"
                                    >
                                        <!-- Seção 1: Edição e Senha -->
                                        <div class="py-1">
                                            <!-- Editar Usuário -->
                                            <button 
                                                type="button" 
                                                @click="abrirEditar({{ json_encode($user) }}); menuOpen = false"
                                                class="w-full text-left px-4 py-2 hover:bg-slate-50 flex items-center gap-2.5 text-slate-700 cursor-pointer"
                                            >
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar usuário
                                            </button>

                                            <!-- Redefinir Senha -->
                                            <button 
                                                type="button" 
                                                @click="usuarioSelecionado = {{ json_encode($user) }}; modalAberta = 'redefinirSenha'; menuOpen = false"
                                                class="w-full text-left px-4 py-2 hover:bg-slate-50 flex items-center gap-2.5 text-slate-700 cursor-pointer"
                                            >
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                                Redefinir senha
                                            </button>
                                        </div>

                                        <!-- Seção 2: Permissões de Cargo -->
                                        <div class="py-1">
                                            @if($isAdmin)
                                                <!-- Remover como Administrador (somente para Admins) -->
                                                <button 
                                                    type="button" 
                                                    @click="usuarioSelecionado = {{ json_encode($user) }}; modalAberta = 'removerAdmin'; menuOpen = false"
                                                    class="w-full text-left px-4 py-2 hover:bg-amber-50/60 flex items-center gap-2.5 text-amber-700 cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/></svg>
                                                    Remover como admin
                                                </button>
                                            @else
                                                <!-- Tornar Administrador (para Colaboradores) -->
                                                <button 
                                                    type="button" 
                                                    @click="usuarioSelecionado = {{ json_encode($user) }}; modalAberta = 'tornarAdmin'; menuOpen = false"
                                                    class="w-full text-left px-4 py-2 hover:bg-blue-50/60 flex items-center gap-2.5 text-blue-700 cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                    Tornar administrador
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Seção 3: Ativação / Desativação de Usuário -->
                                        <div class="py-1">
                                            @if($statusNormalized === 'inativo')
                                                <!-- Reativar Usuário -->
                                                <button 
                                                    type="button" 
                                                    @click="usuarioSelecionado = {{ json_encode($user) }}; modalAberta = 'reativar'; menuOpen = false"
                                                    class="w-full text-left px-4 py-2 hover:bg-emerald-50/60 flex items-center gap-2.5 text-emerald-700 font-medium cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Reativar usuário
                                                </button>
                                            @else
                                                <!-- Desativar Usuário -->
                                                <button 
                                                    type="button" 
                                                    @click="usuarioSelecionado = {{ json_encode($user) }}; modalAberta = 'desativar'; menuOpen = false"
                                                    class="w-full text-left px-4 py-2 hover:bg-rose-50/60 flex items-center gap-2.5 text-rose-600 cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    Desativar usuário
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Seção 4: Exclusão Permanente -->
                                        <div class="py-1">
                                            <button 
                                                type="button" 
                                                @click="usuarioSelecionado = {{ json_encode($user) }}; modalAberta = 'removerUsuario'; menuOpen = false"
                                                class="w-full text-left px-4 py-2 hover:bg-rose-50 flex items-center gap-2.5 text-rose-700 font-semibold cursor-pointer"
                                            >
                                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Remover usuário
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">Nenhum usuário encontrado</p>
                                    <p class="text-xs text-slate-400 mt-1">Tente ajustar seus termos de busca ou filtros.</p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Rodapé da Tabela / Paginação Dinâmica -->
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">
                    Mostrando {{ $usuarios->firstItem() ?? 0 }} a {{ $usuarios->lastItem() ?? 0 }} de {{ $usuarios->total() }} usuários
                </span>
                <div>
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>

        <!-- Inclusão dos Modais do Painel Administrador -->
        @include('components.admin.modal.convidar-usuario')
        @include('components.admin.modal.editar-usuario')
        @include('components.admin.modal.tornar-admin')
        @include('components.admin.modal.remover-admin')
        @include('components.admin.modal.desativar-usuario')
        @include('components.admin.modal.reativar-usuario')
        @include('components.admin.modal.remover-usuario')
        @include('components.admin.modal.redefinir-senha')

    </main>

</body>
</html>
