<template x-teleport="body">
    <div 
        x-show="modalAberta === 'editarUsuario'" 
        x-cloak
        style="display: none;"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
        @keydown.escape.window="modalAberta = null"
    >
        <div 
            @click.away="modalAberta = null"
            class="bg-white w-full max-w-[500px] rounded-[24px] shadow-2xl border border-slate-100 p-8 relative my-auto max-h-[90vh] overflow-y-auto"
        >
            
            <!-- Botão Fechar (X) -->
            <button 
                type="button" 
                @click="modalAberta = null" 
                class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-xl font-bold text-slate-900 mb-1">Editar usuário</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                Atualize as informações cadastrais e a função deste usuário.
            </p>

            <form 
                method="POST" 
                :action="'{{ url('admin/usuarios') }}/' + (usuarioSelecionado ? usuarioSelecionado.id : '')" 
                class="space-y-4"
            >
                @csrf
                @method('PUT')

                <!-- Campo Nome -->
                <div>
                    <label for="edit_nome" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nome completo</label>
                    <input 
                        type="text" 
                        id="edit_nome"
                        name="nome"
                        :value="usuarioSelecionado ? (usuarioSelecionado.name || usuarioSelecionado.nome || '') : ''"
                        required
                        placeholder="Nome do usuário" 
                        class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors"
                    />
                </div>

                <!-- Campo E-mail -->
                <div>
                    <label for="edit_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">E-mail</label>
                    <input 
                        type="email" 
                        id="edit_email"
                        name="email"
                        :value="usuarioSelecionado ? usuarioSelecionado.email : ''"
                        required
                        placeholder="nome@recantobetel.org" 
                        class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors"
                    />
                </div>

                <!-- Campo Telefone -->
                <div>
                    <label for="edit_telefone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Telefone</label>
                    <input 
                        type="text" 
                        id="edit_telefone"
                        name="telefone"
                        :value="usuarioSelecionado ? (usuarioSelecionado.phone || usuarioSelecionado.telefone || '') : ''"
                        placeholder="(00) 00000-0000" 
                        class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors"
                    />
                </div>

                <!-- Seleção de Função -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Função</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:border-slate-300 cursor-pointer bg-white transition-all text-sm font-medium text-slate-800">
                            <input 
                                type="radio" 
                                name="role" 
                                value="colaborador" 
                                :checked="usuarioSelecionado && (usuarioSelecionado.role === 'colaborador' || !usuarioSelecionado.role)"
                                class="text-blue-600 focus:ring-blue-500"
                            >
                            <span>Colaborador</span>
                        </label>

                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:border-slate-300 cursor-pointer bg-white transition-all text-sm font-medium text-slate-800">
                            <input 
                                type="radio" 
                                name="role" 
                                value="administrador" 
                                :checked="usuarioSelecionado && usuarioSelecionado.role === 'administrador'"
                                class="text-blue-600 focus:ring-blue-500"
                            >
                            <span>Administrador</span>
                        </label>
                    </div>
                </div>

                <!-- Seleção de Status -->
                <div>
                    <label for="edit_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Status da conta</label>
                    <select 
                        id="edit_status"
                        name="status"
                        class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-blue-600 transition-colors"
                    >
                        <option value="ativo" :selected="usuarioSelecionado && usuarioSelecionado.status === 'ativo'">Ativo</option>
                        <option value="pendente" :selected="usuarioSelecionado && usuarioSelecionado.status === 'pendente'">Pendente</option>
                        <option value="inativo" :selected="usuarioSelecionado && usuarioSelecionado.status === 'inativo'">Inativo</option>
                    </select>
                </div>

                <!-- Botões de Ação -->
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <button 
                        type="button" 
                        @click="modalAberta = null" 
                        class="w-full py-3 px-4 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:bg-slate-50 transition-colors text-sm cursor-pointer"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-[#2563eb] hover:bg-blue-700 text-white font-semibold transition-colors text-sm shadow-sm cursor-pointer"
                    >
                        Salvar alterações
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>
