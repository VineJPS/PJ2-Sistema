<div 
    x-show="modalAberta === 'editarUsuario'" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 overflow-y-auto"
    @keydown.escape.window="modalAberta = null"
>
    <div 
        @click.away="modalAberta = null"
        class="bg-white w-full max-w-[520px] rounded-[24px] shadow-2xl border border-slate-100 p-8 relative my-auto max-h-[90vh] overflow-y-auto"
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
            Atualize as informações cadastrais, função e status deste usuário.
        </p>

        <!-- Formulário de Edição -->
        <form 
            method="POST" 
            :action="'{{ url('admin/usuarios') }}/' + (editForm.id || (usuarioSelecionado ? usuarioSelecionado.id : ''))" 
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
                    x-model="editForm.nome"
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
                    x-model="editForm.email"
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
                    x-model="editForm.telefone"
                    placeholder="(00) 00000-0000" 
                    class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors"
                />
            </div>

            <!-- Seleção de Função -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Função</label>
                <div class="grid grid-cols-2 gap-3">
                    <label 
                        :class="editForm.role === 'colaborador' ? 'border-2 border-blue-600 bg-blue-50/30' : 'border border-slate-200 bg-white hover:border-slate-300'"
                        class="flex items-center gap-2.5 p-3 rounded-xl cursor-pointer transition-all text-sm font-medium text-slate-800"
                    >
                        <input 
                            type="radio" 
                            name="role" 
                            value="colaborador" 
                            x-model="editForm.role"
                            class="text-blue-600 focus:ring-blue-500"
                        >
                        <div>
                            <span class="font-semibold block text-xs">Colaborador</span>
                            <span class="text-[11px] text-slate-500 block">Acesso padrão</span>
                        </div>
                    </label>

                    <label 
                        :class="editForm.role === 'administrador' ? 'border-2 border-blue-600 bg-blue-50/30' : 'border border-slate-200 bg-white hover:border-slate-300'"
                        class="flex items-center gap-2.5 p-3 rounded-xl cursor-pointer transition-all text-sm font-medium text-slate-800"
                    >
                        <input 
                            type="radio" 
                            name="role" 
                            value="administrador" 
                            x-model="editForm.role"
                            class="text-blue-600 focus:ring-blue-500"
                        >
                        <div>
                            <span class="font-semibold block text-xs">Administrador</span>
                            <span class="text-[11px] text-slate-500 block">Acesso total</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Seleção de Status -->
            <div>
                <label for="edit_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Status da conta</label>
                <select 
                    id="edit_status"
                    name="status"
                    x-model="editForm.status"
                    class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-blue-600 transition-colors"
                >
                    <option value="ativo">Ativo</option>
                    <option value="pendente">Pendente</option>
                    <option value="inativo">Inativo</option>
                </select>
            </div>

            <!-- Campo Nova Senha (Opcional) -->
            <div>
                <label for="edit_senha" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nova senha (opcional)</label>
                <input 
                    type="password" 
                    id="edit_senha"
                    name="senha"
                    placeholder="Deixe em branco para manter a atual" 
                    class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors"
                    autocomplete="new-password"
                />
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
