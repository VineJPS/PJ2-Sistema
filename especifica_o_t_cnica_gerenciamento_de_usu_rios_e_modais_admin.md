# 📋 Especificação Técnica: Gerenciamento de Usuários e Modais Administrador

Este documento instrui a implementação completa do painel de **Gerenciamento de Usuários** no Laravel (Blade + Tailwind CSS), integrando as rotas protegidas por **Laravel Sanctum (Sessions)**, o controle de estado dos modais e as operações de CRUD com envio de e-mails via **Mailtrap**.

---

## 📂 1. Estrutura de Arquivos e Componentes Blade

Organize os arquivos das views no projeto conforme o padrão estabelecido:

```text
resources/views/
├── components/
│   ├── sidebar.blade.php
│   └── admin/
│       ├── index.blade.php (ou usuarios.blade.php)
│       └── modal/
│           ├── convidar-usuario.blade.php
│           ├── remover-admin.blade.php
│           ├── desativar-usuario.blade.php
│           ├── remover-usuario.blade.php
│           └── redefinir-senha.blade.php
```

---

## ⚙️ 2. Regras de Negócio e Métodos do Backend (`UsuarioController`)

Certifique-se de que o `UsuarioController` atenda aos seguintes requisitos funcionais:

### A. Listagem, Busca e Filtros (`listar`)
* **Pesquisa**: Filtrar por `name` ou `email` via parâmetro de busca (`?search=`).
* **Filtro de Abas**:
  * **Todos**: Retorna todos os usuários.
  * **Administradores**: Filtra por `role = 'administrador'` (ou nível equivalente).
  * **Colaboradores**: Filtra por `role = 'colaborador'`.
* **Paginação**: Retornar resultados paginados (ex: `paginate(10)`).

### B. Convidar Usuário (`salvar` / `convidar`)
* **Ação**: Criar um novo registro de usuário com os dados informados no modal (`email`, `role`, status inicial `'pendente'`).
* **Senha Temporária & E-mail**:
  * Gerar uma senha temporária aleatória (ex: `Str::random(10)` ou `Str::password()`).
  * Persistir a senha criptografada (`Hash::make($tempPassword)`).
  * Enviar um e-mail com as credenciais temporárias utilizando a fachada `Mail::to($user->email)->send(new ConviteUsuarioMail($tempPassword));`.
  * Configurar o driver de e-mail no `.env` para usar o **Mailtrap** (`MAIL_MAILER=smtp`, `MAIL_HOST=sandbox.smtp.mailtrap.io`, etc.).

### C. Alterar Nível de Acesso (`removerAdmin`)
* Atualizar a função do usuário (`role`) de `'administrador'` para `'colaborador'`.

### D. Alterar Status do Usuário (`desativar`)
* Atualizar o atributo `status` do usuário para `'inativo'`.

### E. Redefinição de Senha (`redefinirSenha`)
* Disparar o fluxo padrão do Laravel para envio do link de redefinição de senha ou reemitir uma nova senha temporária por e-mail via Mailtrap.

### F. Exclusão de Usuário (`deletar`)
* Remover o usuário do banco de dados (exclusão física ou via `SoftDeletes`).

---

## 🛣️ 3. Mapeamento de Rotas (`routes/web.php`)

Garantir que as rotas administrativas estejam devidamente protegidas pelo middleware `auth:sanctum`:

```php
use App\Http\Controllers\UsuarioController;

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'listar'])->name('index');
        Route::post('/convidar', [UsuarioController::class, 'salvar'])->name('convidar');
        Route::put('/{id}/remover-admin', [UsuarioController::class, 'removerAdmin'])->name('removerAdmin');
        Route::put('/{id}/desativar', [UsuarioController::class, 'desativar'])->name('desativar');
        Route::post('/{id}/redefinir-senha', [UsuarioController::class, 'redefinirSenha'])->name('redefinirSenha');
        Route::delete('/{id}', [UsuarioController::class, 'deletar'])->name('destroy');
    });

});
```

---

## 🎯 4. Interatividade dos Modais (Alpine.js / Livewire)

Para controlar a abertura e o fechamento dos 5 modais na listagem sem recarregar a página, utilize **Alpine.js**:

* Definir um estado central no container principal da tabela:
  `x-data="{ modalAberta: null, usuarioSelecionado: null }"`
* Atribuir identificadores aos modais:
  * `'convidar'`
  * `'removerAdmin'`
  * `'desativar'`
  * `'removerUsuario'`
  * `'redefinirSenha'`
* Ao clicar no menu de ações de uma linha da tabela, carregar os dados do usuário no estado (`usuarioSelecionado = usuario`) e definir o modal ativo (`modalAberta = 'desativar'`).
* Em cada modal (`components/admin/modal/*.blade.php`), usar `x-show="modalAberta === 'NOME_DO_MODAL'"` e atribuir `@click="modalAberta = null"` no botão de **Cancelar** ou no ícone de fechar (`X`).

---

## ✅ 5. Check-list de Validação

1. **Configuração do Mailtrap**: O arquivo `.env` deve conter as credenciais ativas do Mailtrap para testes de envio de e-mail.
2. **Componente Sidebar**: A tela deve carregar `<x-sidebar />` com a aba "Administrador" destacada como ativa.
3. **Persistência de Dados**: Ao submeter o formulário de convite, o registro deve ser inserido na tabela `users` com status `pendente` e a mensagem de e-mail deve ser capturada na caixa de entrada do Mailtrap.
4. **Respostas Visuais**: Tratar feedback de sucesso/erro via mensagens flash de sessão (`session('success')` / `session('error')`).