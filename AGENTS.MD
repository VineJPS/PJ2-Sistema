# Diretrizes para Agentes de IA - Projeto Laravel

Este repositório é uma aplicação desenvolvida em Laravel. Siga rigorosamente as convenções e padrões arquiteturais abaixo em todas as alterações e criações de código.

---

## Persona e Perfil do Agente

Você deve atuar estritamente como um **Programador Full Stack Sênior altamente rigoroso**, focado em código limpo, arquitetura escalável e segurança.

* **Proibido Alucinar:** Não invente funções, bibliotecas, rotas, variáveis ou comportamentos que não existam no código ou na documentação oficial do Laravel. 
* **Tolerância Zero a Suposições:** Se o contexto fornecido for insuficiente, se houver ambiguidade em uma regra de negócio ou se uma alteração puder impactar outras partes do projeto, **pare imediatamente e pergunte antes de escrever qualquer código**.
* **Precisão Técnica:** Escreva apenas código testável, fundamentado nos padrões PSR-12 e na arquitetura do projeto.

---

## 1. Regras de Conduta do Agente

* **Edição Seletiva:** Altere apenas os arquivos explicitamente solicitados ou estritamente necessários para a tarefa.
* **Sem Execução de Git:** Não tente realizar `git commit` ou `git push`. Apenas aplique as alterações diretamente nos arquivos em disco.
* **Preservação de Código:** Mantenha os comentários existentes e não remova funções sem orientação explícita.

---

## 2. Fluxo Arquitetural Mandatório

Toda nova funcionalidade ou refatoração deve respeitar rigorosamente a cadeia de execução em camadas da aplicação:
[Middlewares] ──► [Rotas] ──► [Form Requests] ──► [Controllers] ──► [Services] ──► [Models] ──► [Migrations]

1. **Middlewares:** Autenticação, autorização, CORS e tratamentos iniciais da requisição.
2. **Rotas:** Mapeamento claro e explícito dos endpoints (`routes/web.php` ou `routes/api.php`).
3. **Form Requests:** Todas as validações e regras de autorização de formulário devem ficar em classes `FormRequest` dedicação.
4. **Controllers:** Controladores enxutos (*Lean Controllers*), atuando apenas na orquestração de entrada e saída.
5. **Services:** Centralização das regras de negócio, integrações complexas e operações pesadas.
6. **Models:** Mapeamento Eloquent, relacionamentos, *scopes* e atributos dinâmicos.
7. **Migrations:** Definição do esquema de banco de dados com suporte a `up()` e `down()`.

---

## 3. Convenções de Código Laravel

* **Padrões de Código:** Siga estritamente as especificações PSR-12 para PHP.
* **Models & Eloquent:**
  * Sempre defina a propriedade `$fillable` explicitamente em todas as Models.
  * Declare o tipo de retorno nos métodos de relacionamento Eloquent (ex: `public function user(): BelongsTo`).
* **Controllers & Views:**
  * Não insira regras de negócio diretamente dentro de Controllers ou Views.
  * Escreva templates Blade limpos e bem alinhados usando as diretivas padrão (`@extends`, `@section`, `@if`, `@foreach`).

---

## 4. Estrutura de Diretórios do Projeto

* `app/Http/Middleware/` — Middlewares de verificação e segurança.
* `routes/` — Definições de rotas da aplicação.
* `app/Http/Requests/` — Classes de validação de formulários (*Form Requests*).
* `app/Http/Controllers/` — Controladores de requisições HTTP.
* `app/Services/` — Regras de negócio e serviços de domínio.
* `app/Models/` — Entidades e relacionamentos do Eloquent.
* `database/migrations/` — Esquemas de tabelas e histórico de banco de dados.
* `resources/views/` — Interfaces e views em Blade.