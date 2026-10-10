<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden">

    <!-- Incluindo o Componente Sidebar Fixa -->
    <x-sidebar />

    <!-- Conteúdo Principal -->
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        
        <!-- Feedback de Notificação de Sucesso -->
        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('sucesso') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <!-- Feedback de Erros de Validação -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-semibold text-red-800">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Por favor, verifique os campos abaixo:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-red-600 pl-2">
                    @foreach($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Cabeçalho do Perfil (Avatar e Dados principais) -->
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 rounded-full bg-[#e2e8f0] flex items-center justify-center text-[#1e3a8a] font-bold text-xl">
                @if(auth()->check() && (auth()->user()->name || auth()->user()->nome))
                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nome, 0, 2)) }}
                @else
                    <!-- Ícone de Usuário -->
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @endif
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-[#1e293b]">
                    {{ auth()->user()->name ?? auth()->user()->nome ?? 'Usuário' }}
                </h2>
                <p class="text-[#64748b] text-sm md:text-base">
                    {{ auth()->user()->email ?? 'Não informado' }}
                </p>
            </div>
        </div>

        <!-- Seção 1: Dados Pessoais com suporte a Edição Interativa -->
        <div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }" class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm mb-6 overflow-hidden">
            
            <!-- MODO VISUALIZAÇÃO -->
            <div x-show="!editing">
                <!-- Cabeçalho do Card -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#f1f5f9]">
                    <div>
                        <h3 class="text-base md:text-lg font-bold text-[#1e3a8a]">Dados Pessoais</h3>
                        <p class="text-xs md:text-sm text-[#64748b]">Documentos e identificação civil</p>
                    </div>
                    <button @click="editing = true" type="button" class="flex items-center gap-1.5 text-sm font-semibold text-[#1d4ed8] hover:underline cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                </div>

                <!-- Grid de Informações Pessoais -->
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#f1f5f9]">
                    <!-- Linha 1 / Coluna 1 -->
                    <div class="p-6">
                        <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">Nome completo</span>
                        <span class="text-sm md:text-base text-[#1e293b] font-medium">
                            {{ auth()->user()->name ?? auth()->user()->nome ?? 'Não informado' }}
                        </span>
                    </div>
                    <!-- Linha 1 / Coluna 2 -->
                    <div class="p-6">
                        <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">E-mail</span>
                        <span class="text-sm md:text-base text-[#1e293b] font-medium">
                            {{ auth()->user()->email ?? 'Não informado' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#f1f5f9] border-t border-[#f1f5f9]">
                    <!-- Linha 2 / Coluna 1 -->
                    <div class="p-6">
                        <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">Telefone</span>
                        <span class="text-sm md:text-base text-[#1e293b] font-medium">
                            {{ auth()->user()->phone ?? auth()->user()->telefone ?? 'Não informado' }}
                        </span>
                    </div>
                    <!-- Linha 2 / Coluna 2 -->
                    <div class="p-6">
                        <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">CPF</span>
                        <span class="text-sm md:text-base text-[#1e293b] font-medium">
                            {{ auth()->user()->cpf ?? 'Não informado' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- MODO EDIÇÃO -->
            <form x-show="editing" method="POST" action="{{ route('profile.update') }}" style="display: none;">
                @csrf
                @method('PUT')

                <!-- Cabeçalho do Card em Edição -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#f1f5f9] bg-[#f8fafc]">
                    <div>
                        <h3 class="text-base md:text-lg font-bold text-[#1e3a8a]">Editar Dados Pessoais</h3>
                        <p class="text-xs md:text-sm text-[#64748b]">Atualize suas informações de cadastro</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="editing = false" type="button" class="px-4 py-2 text-sm font-semibold text-[#64748b] bg-white border border-[#cbd5e1] rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-[#1d4ed8] rounded-xl hover:bg-[#1e40af] shadow-sm transition-colors cursor-pointer">
                            Salvar alterações
                        </button>
                    </div>
                </div>

                <!-- Grid de Campos de Edição -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Nome -->
                    <div>
                        <label for="nome" class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">Nome completo *</label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="{{ old('nome', auth()->user()->nome ?? auth()->user()->name) }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-[#cbd5e1] focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 outline-none text-sm text-[#1e293b] transition-all"
                        />
                        @error('nome')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo E-mail -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">E-mail *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-[#cbd5e1] focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 outline-none text-sm text-[#1e293b] transition-all"
                        />
                        @error('email')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Telefone -->
                    <div>
                        <label for="telefone" class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">Telefone</label>
                        <input 
                            type="text" 
                            id="telefone" 
                            name="telefone" 
                            value="{{ old('telefone', auth()->user()->telefone ?? auth()->user()->phone) }}" 
                            placeholder="(00) 00000-0000"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#cbd5e1] focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 outline-none text-sm text-[#1e293b] transition-all"
                        />
                        @error('telefone')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo CPF -->
                    <div>
                        <label for="cpf" class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">CPF</label>
                        <input 
                            type="text" 
                            id="cpf" 
                            name="cpf" 
                            value="{{ old('cpf', auth()->user()->cpf) }}" 
                            placeholder="000.000.000-00"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#cbd5e1] focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 outline-none text-sm text-[#1e293b] transition-all"
                        />
                        @error('cpf')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Nova Senha (Opcional) -->
                    <div class="md:col-span-2 pt-2 border-t border-[#f1f5f9]">
                        <label for="senha" class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">Alterar senha</label>
                        <p class="text-xs text-[#64748b] mb-2">Deixe em branco caso deseje manter a senha atual.</p>
                        <input 
                            type="password" 
                            id="senha" 
                            name="senha" 
                            placeholder="Nova senha (mínimo 6 caracteres)"
                            class="w-full max-w-md px-4 py-2.5 rounded-xl border border-[#cbd5e1] focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 outline-none text-sm text-[#1e293b] transition-all"
                        />
                        @error('senha')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        <!-- Seção 2: Acesso e Permissões -->
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            <!-- Cabeçalho do Card -->
            <div class="px-6 py-5 border-b border-[#f1f5f9]">
                <h3 class="text-base md:text-lg font-bold text-[#1e3a8a]">Acesso e permissões</h3>
            </div>

            <!-- Grid de Permissões -->
            <div class="grid grid-cols-1 md:grid-cols-2 p-6 gap-6 items-center">
                <div>
                    <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">Nível de acesso</span>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#eff6ff] text-[#1d4ed8] text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#1d4ed8]"></span>
                        {{ auth()->user()->nivel->nome ?? 'Usuário' }}
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">Status da conta</span>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#f0fdf4] text-[#16a34a] text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#16a34a]"></span>
                        {{ auth()->user()->status ?? 'Ativa' }}
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>
</html>