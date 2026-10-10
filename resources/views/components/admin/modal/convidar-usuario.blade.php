<div 
    x-show="modalAberta === 'convidar'" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
    @keydown.escape.window="modalAberta = null"
>
    <div 
        @click.away="modalAberta = null"
        class="bg-white w-full max-w-[500px] rounded-[24px] shadow-2xl border border-slate-100 p-8 relative"
    >
        
        <!-- Botão Fechar (X) -->
        <button 
            type="button" 
            @click="modalAberta = null" 
            class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="text-xl font-bold text-slate-900 mb-2">Convidar usuário</h3>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Envie um convite por e-mail para dar acesso ao painel.
        </p>

        <form method="POST" action="{{ route('admin.usuarios.convidar') }}" class="space-y-6">
            @csrf

            <!-- Campo E-mail -->
            <div>
                <label for="convite_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">E-mail</label>
                <input 
                    type="email" 
                    id="convite_email"
                    name="email"
                    required
                    placeholder="nome@recantobetel.org" 
                    class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors"
                />
            </div>

            <!-- Seleção de Função -->
            <div x-data="{ selectedRole: 'colaborador' }">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Função</label>
                <div class="space-y-3">
                    
                    <!-- Radio 1: Colaborador -->
                    <label 
                        @click="selectedRole = 'colaborador'"
                        :class="selectedRole === 'colaborador' ? 'border-2 border-blue-600 bg-blue-50/40' : 'border border-slate-200 bg-white hover:border-slate-300'"
                        class="flex items-start gap-3 p-4 rounded-xl cursor-pointer transition-all"
                    >
                        <input 
                            type="radio" 
                            name="funcao" 
                            value="colaborador" 
                            x-model="selectedRole"
                            class="mt-1 text-blue-600 focus:ring-blue-500"
                        >
                        <div>
                            <span class="block font-semibold text-slate-900 text-sm">Colaborador</span>
                            <span class="block text-xs text-slate-500 mt-0.5 leading-relaxed">Acessa crianças, matrículas e relatos. Não pode gerenciar outros usuários.</span>
                        </div>
                    </label>

                    <!-- Radio 2: Administrador -->
                    <label 
                        @click="selectedRole = 'administrador'"
                        :class="selectedRole === 'administrador' ? 'border-2 border-blue-600 bg-blue-50/40' : 'border border-slate-200 bg-white hover:border-slate-300'"
                        class="flex items-start gap-3 p-4 rounded-xl cursor-pointer transition-all"
                    >
                        <input 
                            type="radio" 
                            name="funcao" 
                            value="administrador" 
                            x-model="selectedRole"
                            class="mt-1 text-blue-600 focus:ring-blue-500"
                        >
                        <div>
                            <span class="block font-semibold text-slate-900 text-sm">Administrador</span>
                            <span class="block text-xs text-slate-500 mt-0.5 leading-relaxed">Acesso completo, incluindo gerenciar usuários e permissões.</span>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="grid grid-cols-2 gap-4 pt-2">
                <button 
                    type="button" 
                    @click="modalAberta = null" 
                    class="w-full py-3.5 px-4 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:bg-slate-50 transition-colors text-sm cursor-pointer"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl bg-[#2563eb] hover:bg-blue-700 text-white font-semibold transition-colors text-sm shadow-sm cursor-pointer"
                >
                    Enviar convite
                </button>
            </div>
        </form>

    </div>
</div>