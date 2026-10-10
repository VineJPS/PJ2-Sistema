# 📋 Guia de Implementação: Autenticação via Sanctum (Sessions) e Integração de Telas

Este documento orienta o agente de desenvolvimento na validação e ajuste da estrutura de autenticação existente (utilizando **Laravel Sanctum** com **Stateful Sessions**), bem como na conexão dos métodos e dados dinâmicos nas telas de **Login** e **Perfil do Usuário** do sistema Recanto Betel. Revise o projeto antes de aplicar as correções

---

## 1. Validação e Ajuste do Backend (Autenticação via Sessions)

Como o projeto já possui o Sanctum configurado, certifique-se de que a infraestrutura está adequada para autenticação baseada em sessões (SPA/Web guard):

* **Middleware Stateful**: Verifique no arquivo `app/Http/Kernel.php` (ou bootstrap correspondente, dependendo da versão do Laravel) se o middleware do Sanctum (`EnsureFrontendRequestsAreStateful`) está ativo para o grupo web, ou se o guard padrão está configurado para autenticar via sessão.
* **Controlador de Autenticação (`AutenticacaoController`)**:
  - Valide se o método responsável por entrar (`entrar`) utiliza a tentativa de autenticação por sessão do Laravel (`Auth::attempt($credentials)`) combinada com a regeneração da sessão (`$request->session()->regenerate()`) para garantir a segurança via Sanctum.
  - Certifique-se de que o método de saída (`sair`) invalida a sessão atual e regenera o token CSRF (`Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();`).

---

## 2. Ajustes na Tela de Login

No arquivo Blade de login (`resources/views/components/usuario/login.blade.php`), realize as seguintes implementações:

* **Formulário POST**: Configure a tag `<form>` para apontar para a rota de submissão de login utilizando o método `POST` e incluindo a diretiva de segurança `@csrf`.
* **Vinculação de Inputs (`name`)**:
  - Garanta que o campo de e-mail possua o atributo `name="email"`.
  - Garanta que o campo de senha possua o atributo `name="password"`.
* **Feedback de Erros**: Adicione blocos condicionais do Blade (`@error('email')`) para exibir mensagens visuais de erro caso as credenciais fornecidas sejam inválidas.

---

## 3. Ajustes na Tela de Perfil do Usuário

No arquivo Blade de perfil (`resources/views/components/usuario/perfil.blade.php`), substitua os dados estáticos mockados pelas variáveis injetadas pelo backend (através do usuário autenticado atual):

* **Exibição Dinâmica de Dados**:
  - Utilize o objeto do usuário logado (`auth()->user()`) para preencher os campos:
    - Nome completo: `{{ auth()->user()->name }}` (ou campo equivalente na base).
    - E-mail: `{{ auth()->user()->email }}`.
    - Telefone e CPF: Mapeie para os atributos correspondentes na model de usuário (`{{ auth()->user()->phone }}` / `{{ auth()->user()->cpf }}`).
    - Status e Nível de Acesso: Renderize dinamicamente as badges com base nas permissões do utilizador.
* **Ações de Conta**:
  - Conecte o botão ou formulário de **Sair** na sidebar / menu flutuante para disparar uma requisição `POST` para a rota de logout.
  - Garanta que o link "Meu perfil" aponte corretamente para a rota de visualização do perfil autenticado.

---