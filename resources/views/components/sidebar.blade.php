<aside aria-label="Menu Lateral" class="w-72 bg-[#1746b1] h-screen sticky top-0 flex flex-col justify-between p-6 text-white shrink-0 overflow-y-auto">
    <!-- Topo da Sidebar: Logo e Links -->
    <div>
        <h1 class="text-xl font-bold tracking-tight mb-8">Recanto Betel</h1>

        <nav class="flex flex-col gap-2">
            <!-- Item Início (Dashboard) -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-[#265ae1] text-white font-medium shadow-sm' : 'text-blue-100 hover:bg-[#2050c7]' }} transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Início
            </a>

            <!-- Item Crianças -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('home') || request()->is('crianca*') ? 'bg-[#265ae1] text-white font-medium shadow-sm' : 'text-blue-100 hover:bg-[#2050c7]' }} transition-all">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Crianças
            </a>

            <!-- Item Matrícula -->
            <a href="{{ route('matricula.crianca') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('matricula*') ? 'bg-[#265ae1] text-white font-medium shadow-sm' : 'text-blue-100 hover:bg-[#2050c7]' }} transition-all">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Matrícula
            </a>

            <!-- Item Relatos -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('relatos*') ? 'bg-[#265ae1] text-white font-medium shadow-sm' : 'text-blue-100 hover:bg-[#2050c7]' }} transition-all">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Relatos
            </a>
            
            <!-- Item Administrador -->
            <!-- Seção Sistema (Visível apenas para Administradores) -->
            @if(auth()->check() && auth()->user()->role === 'administrador')
                <div class="mt-8 mb-2 px-4 text-xs font-semibold tracking-wider text-blue-300 uppercase">Sistema</div>
                
                <!-- Item Administrador -->
                <a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.*') ? 'bg-[#265ae1] text-white font-medium shadow-sm' : 'text-blue-100 hover:bg-[#2050c7]' }} transition-all">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Administrador
                </a>
            @endif
        </nav>
    </div>

    <!-- Área do Perfil com Dropdown (Controlado por Alpine.js) -->
    <div class="relative" x-data="{ open: false }">
        
        <!-- Menu Flutuante (Popup Branco) -->
        <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 w-full mb-3 bg-white rounded-2xl shadow-xl overflow-hidden text-slate-800 border border-slate-100 py-1" style="display: none;">
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors text-sm font-medium text-slate-700">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Meu perfil
            </a>
            
            <div class="h-[1px] bg-slate-100 mx-4"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-5 py-3.5 hover:bg-red-50 transition-colors text-sm font-medium text-red-600 cursor-pointer">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sair
                </button>
            </form>
        </div>

        <!-- Botão Rodapé da Sidebar (Gatilho do Menu) -->
        <button @click="open = !open" class="w-full flex items-center justify-between pt-4 mt-2 border-t border-blue-600/50 bg-[#1746b1] text-left focus:outline-none group cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-500/50 flex items-center justify-center font-bold text-sm text-white">
                    @if(auth()->check() && (auth()->user()->name || auth()->user()->nome))
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nome, 0, 2)) }}
                    @else
                        U
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold leading-tight text-white">
                        {{ auth()->check() ? (auth()->user()->name ?? auth()->user()->nome) : 'Usuário' }}
                    </p>
                    <p class="text-xs text-blue-200">
                        {{ auth()->check() ? (auth()->user()->nivel->nome ?? 'Usuário') : 'Convidado' }}
                    </p>
                </div>
            </div>
            <!-- Seta rotaciona baseada no estado 'open' -->
            <svg class="w-4 h-4 text-blue-300 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </div>
</aside>

<!-- Importação do Alpine.js no layout caso ainda não tenha (necessário para o dropdown interativo) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>