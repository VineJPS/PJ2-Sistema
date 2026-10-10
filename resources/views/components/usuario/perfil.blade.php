<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden">

    <!-- Incluindo o Componente Sidebar Fixa -->
    <x-sidebar />

    <!-- Conteúdo Principal -->
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        
        <!-- Cabeçalho do Perfil (Avatar e Dados principais) -->
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 rounded-full bg-[#e2e8f0] flex items-center justify-center text-[#64748b]">
                <!-- Ícone de Usuário -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-[#1e293b]">Marcus Souza</h2>
                <p class="text-[#64748b] text-sm md:text-base">marcus.souza@recantobetel.org</p>
            </div>
        </div>

        <!-- Seção 1: Dados Pessoais -->
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm mb-6 overflow-hidden">
            <!-- Cabeçalho do Card -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-[#f1f5f9]">
                <div>
                    <h3 class="text-base md:text-lg font-bold text-[#1e3a8a]">Dados Pessoais</h3>
                    <p class="text-xs md:text-sm text-[#64748b]">Documentos e identificação civil</p>
                </div>
                <button class="flex items-center gap-1.5 text-sm font-semibold text-[#1d4ed8] hover:underline">
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
                    <span class="text-sm md:text-base text-[#1e293b] font-medium">Marcus Vinicius de Souza</span>
                </div>
                <!-- Linha 1 / Coluna 2 -->
                <div class="p-6">
                    <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">E-mail</span>
                    <span class="text-sm md:text-base text-[#1e293b] font-medium">marcus.souza@recantobetel.org</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#f1f5f9] border-t border-[#f1f5f9]">
                <!-- Linha 2 / Coluna 1 -->
                <div class="p-6">
                    <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">Telefone</span>
                    <span class="text-sm md:text-base text-[#1e293b] font-medium">(15) 99812-3456</span>
                </div>
                <!-- Linha 2 / Coluna 2 -->
                <div class="p-6">
                    <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-1">CPF</span>
                    <span class="text-sm md:text-base text-[#1e293b] font-medium">123.456.789-10</span>
                </div>
            </div>
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
                        Administrador
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-[#1d4ed8] uppercase tracking-wider mb-2">Status da conta</span>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#f0fdf4] text-[#16a34a] text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#16a34a]"></span>
                        Ativa
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>
</html>