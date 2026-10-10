<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden">

    <!-- Incluindo o Componente Sidebar Fixa -->
    <x-sidebar />

    <!-- Conteúdo Principal (Sem Scroll vertical desnecessário) -->
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        <!-- Cabeçalho de Boas-vindas -->
        <div class="mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1e293b] mb-1">Olá equipe!</h2>
            <p class="text-[#64748b] text-base">Acompanhe aqui as principais informações do Recanto Betel.</p>
        </div>

        <!-- Grid de Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Card 1: Matrículas -->
            <div class="bg-white rounded-2xl p-6 border border-[#22c55e]/40 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-[#15803d] font-bold text-lg mb-1">Matrículas</h3>
                    <p class="text-[#475569] text-sm">Pendentes</p>
                </div>
                <span class="text-4xl md:text-5xl font-bold text-[#15803d]">3</span>
            </div>

            <!-- Card 2: Rematrículas -->
            <div class="bg-white rounded-2xl p-6 border border-[#f59e0b]/40 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-[#b45309] font-bold text-lg mb-1">Rematrículas</h3>
                    <p class="text-[#475569] text-sm">Pendentes</p>
                </div>
                <span class="text-4xl md:text-5xl font-bold text-[#b45309]">7</span>
            </div>

            <!-- Card 3: Informações -->
            <div class="bg-white rounded-2xl p-6 border border-[#3b82f6]/40 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-[#1d4ed8] font-bold text-lg mb-1">Informações</h3>
                    <p class="text-[#475569] text-sm">Pendentes</p>
                </div>
                <span class="text-4xl md:text-5xl font-bold text-[#1d4ed8]">2</span>
            </div>

        </div>
    </main>

</body>
</html>