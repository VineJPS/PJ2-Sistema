<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Confirmar Convite</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f1f5f9] min-h-screen flex flex-col items-center justify-center font-sans antialiased p-4 py-8">

    <!-- Logotipo / Título Principal -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#1746b1] tracking-tight">
            Recanto Betel
        </h1>
        <p class="text-slate-500 text-sm mt-1">Sistema de Gestão</p>
    </div>

    @php
        $roleNormalized = strtolower($usuario->role ?? ($usuario->nivel ? $usuario->nivel->nome : 'colaborador'));
        $isAdmin = $roleNormalized === 'administrador';
    @endphp

    <!-- Card de Confirmação -->
    <div class="w-full max-w-[560px] bg-white rounded-3xl shadow-xl border border-slate-200/80 p-8 md:p-10">
        
        <!-- Ícone Superior -->
        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-5 border border-blue-100 shadow-sm">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Você foi convidado!</h2>
            <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                Confirme suas informações e as permissões de acesso oferecidas para entrar no sistema.
            </p>
        </div>

        <!-- Bloco Informativo de Remetente e Convidado -->
        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/70 space-y-3.5 mb-6 text-sm">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200/60">
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Convite enviado por:</span>
                <span class="text-slate-800 font-medium">{{ $remetente }}</span>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-slate-200/60">
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Conta convidada:</span>
                <div class="text-right">
                    <p class="text-slate-800 font-semibold leading-tight">{{ $usuario->name ?? $usuario->nome }}</p>
                    <p class="text-slate-500 text-xs">{{ $usuario->email }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Nível de acesso:</span>
                @if($isAdmin)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        Administrador
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                        Colaborador
                    </span>
                @endif
            </div>
        </div>

        <!-- Descrição das Permissões do Nível -->
        <div class="mb-8 p-4 rounded-2xl @if($isAdmin) bg-blue-50/60 border border-blue-100 @else bg-slate-100/60 border border-slate-200/60 @endif">
            <h3 class="text-xs font-bold uppercase tracking-wider @if($isAdmin) text-blue-900 @else text-slate-800 @endif mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                O que você poderá fazer:
            </h3>

            @if($isAdmin)
                <ul class="text-xs text-blue-950 space-y-1.5 leading-relaxed pl-1">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold shrink-0">✓</span>
                        <span>Acesso completo ao painel de controle e todas as seções.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold shrink-0">✓</span>
                        <span>Gerenciamento de usuários: convidar, promover, redefinir senhas e desativar contas.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold shrink-0">✓</span>
                        <span>Controle total de crianças, fichas médicas, matrículas e relatos.</span>
                    </li>
                </ul>
            @else
                <ul class="text-xs text-slate-700 space-y-1.5 leading-relaxed pl-1">
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold shrink-0">✓</span>
                        <span>Acesso ao painel principal para consulta e visualização dos dados.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold shrink-0">✓</span>
                        <span>Acesso às informações de crianças, matrículas e relatos.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-slate-400 font-bold shrink-0">✕</span>
                        <span class="text-slate-500">Sem permissão para gerenciar usuários ou alterar configurações administrativas.</span>
                    </li>
                </ul>
            @endif
        </div>

        <!-- Botões de Ação: Aceitar ou Recusar -->
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="POST" action="{{ route('convite.recusar', $usuario->id) }}" class="flex-1 order-2 sm:order-1">
                @csrf
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl border border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-slate-600 font-semibold text-sm transition-all cursor-pointer"
                >
                    Recusar convite
                </button>
            </form>

            <form method="POST" action="{{ route('convite.aceitar', $usuario->id) }}" class="flex-1 order-1 sm:order-2">
                @csrf
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl bg-[#1e40af] hover:bg-blue-800 text-white font-semibold text-sm transition-all shadow-md hover:shadow-lg cursor-pointer"
                >
                    Aceitar convite
                </button>
            </form>
        </div>

    </div>

</body>
</html>
