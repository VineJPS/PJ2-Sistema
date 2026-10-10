# Guia de Migrations e Seeders - Sistema de Gestão de Creche (Laravel + MySQL)

Este documento orienta a criação das tabelas no banco de dados via **Migrations** (respeitando a ordem de dependência das Foreign Keys) e a estruturação de **Seeders** para popular o sistema com dados realistas para testes.

---

## 1. Ordem Correta de Criação das Migrations

Para evitar erros de dependência de chaves estrangeiras (`foreignId`), siga rigorosamente a ordem abaixo:

1. `enderecos` (Independente)
2. `gastos` (Depende de `enderecos`)
3. `responsaveis` (Depende de `enderecos`)
4. `criancas` (Depende de `enderecos`)
5. `crianca_responsavel` (Tabela pivô: depende de `criancas` e `responsaveis`)
6. `fichas_anamnese` (Depende de `criancas`)
7. `anamnese_historico_clinicos` (Depende de `fichas_anamnese`)
8. `anamnese_paths` (Depende de `fichas_anamnese`)

---

### Detalhamento das Migrations

#### 1. Endereços (`create_enderecos_table`)
Armazena a localização das famílias/residências.
* `id`: PK (bigIncrements)
* `estado`: string(2)
* `cidade`: string(100)
* `bairro`: string(100)
* `rua`: string(150)
* `numero`: string(20)
* `ponto_referencia`: text (nullable)
* `moradia`: string(50) (ex: Própria, Alugada, Cedida)
* `timestamps`

#### 2. Gastos (`create_gastos_table`)
Despesas mensais vinculadas ao endereço residencial.
* `id`: PK
* `endereco_id`: FK para `enderecos` (cascade onDelete)
* `agua`: decimal(10,2)
* `energia`: decimal(10,2)
* `alimentacao`: decimal(10,2)
* `gas`: decimal(10,2)
* `timestamps`

#### 3. Responsáveis (`create_responsaveis_table`)
Pais, mães ou responsáveis legais pelas crianças.
* `id`: PK
* `endereco_id`: FK para `enderecos`
* `nome`: string(150)
* `telefone`: string(20)
* `profissao`: string(100) (nullable)
* `grau_escolaridade`: string(50) (nullable)
* `salario`: decimal(10,2) (nullable)
* `nis`: string(20) (nullable)
* `timestamps`

#### 4. Crianças (`create_criancas_table`)
Cadastro principal dos menores atendidos.
* `id`: PK
* `endereco_id`: FK para `enderecos`
* `nome`: string(150)
* `cpf`: string(14) (nullable/unique)
* `rg`: string(20) (nullable)
* `data_nascimento`: date
* `escola`: string(150) (nullable - caso estude em outro turno/escola externa)
* `relatos`: text (nullable)
* `conselho_tutelar`: boolean (default false)
* `timestamps`

#### 5. Criança-Responsável (`create_crianca_responsavel_table`)
Tabela pivô para o relacionamento N:N entre crianças e responsáveis.
* `crianca_id`: FK para `criancas`
* `responsavel_id`: FK para `responsaveis`
* `grau_parentesco`: string(50) (ex: Mãe, Pai, Avó, Tio)
* Chave composta sugerida: `primary(['crianca_id', 'responsavel_id'])`

#### 6. Ficha de Anamnese (`create_fichas_anamnese_table`)
Cabeçalho da ficha de triagem médica e social da criança.
* `id`: PK
* `crianca_id`: FK para `criancas`
* `queixa_principal`: text (nullable)
* `motivo_atendimento`: varchar(255)
* `habitos`: text (nullable)
* `antecedentes_familiares`: text (nullable)
* `outras_informacoes`: text (nullable)
* `data_preenchimento`: date
* `timestamps`

#### 7. Histórico Clínico da Anamnese (`create_anamnese_historico_clinicos_table`)
Questões detalhadas de saúde gerais.
* `id`: PK
* `ficha_anamnese_id`: FK para `fichas_anamnese`
* `teve_doenca`: boolean (default false)
* `quais_doencas`: text (nullable)
* `em_tratamento_medico`: boolean (default false)
* `qual_tratamento`: text (nullable)
* `esta_gravida`: boolean (default false)
* `usa_medicacao`: boolean (default false)
* `quais_medicacoes`: text (nullable)
* `tem_alergia`: boolean (default false)
* `quais_alergias`: text (nullable)
* `ja_foi_operado`: boolean (default false)
* `quais_cirurgias`: text (nullable)
* `problema_visao`: boolean (default false)
* `problema_audicao`: boolean (default false)
* `problema_fala`: boolean (default false)
* `timestamps`

#### 8. Caminho/Patologias Específicas da Anamnese (`create_anamnese_paths_table`)
Detalhamentos sistêmicos adicionais (cardíaco, pulmonar, gastrointestinal, etc.).
* `id`: PK
* `ficha_anamnese_id`: FK para `fichas_anamnese`
* `problemas_cardiacos`: boolean (default false)
* `problemas_pulmonares`: boolean (default false)
* `problemas_gastrointestinais`: boolean (default false)
* `problemas_neurologicos`: boolean (default false)
* `problemas_ortopedicos`: boolean (default false)
* `problemas_alergicos`: boolean (default false)
* `manchas`: text (nullable)
* `observacoes`: text (nullable)
* `timestamps`

---

## 2. Seeders para Popular o Banco de Dados

Para popular o banco ordenadamente na classe `DatabaseSeeder`, chame os seeders na mesma ordem lógica das tabelas.

### Ordem de Execução no `DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        EnderecoSeeder::class,
        GastoSeeder::class,
        ResponsavelSeeder::class,
        CriancaSeeder::class,
        CriancaResponsavelSeeder::class,
        FichaAnamneseSeeder::class,
        AnamneseHistoricoClinicoSeeder::class,
        AnamnesePathSeeder::class,
    ]);
}
```

### Exemplos de Lógica dos Seeders

#### Exemplo: `EnderecoSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnderecoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('enderecos')->insert([
            [
                'id' => 1,
                'estado' => 'SP',
                'cidade' => 'São Paulo',
                'bairro' => 'Jardim Primavera',
                'rua' => 'Rua das Flores',
                'numero' => '123',
                'ponto_referencia' => 'Próximo à praça central',
                'moradia' => 'Alugada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'estado' => 'SP',
                'cidade' => 'São Paulo',
                'bairro' => 'Vila Esperança',
                'rua' => 'Avenida Brasil',
                'numero' => '456',
                'ponto_referencia' => 'Em frente ao mercado',
                'moradia' => 'Própria',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

#### Exemplo: `GastoSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GastoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gastos')->insert([
            [
                'endereco_id' => 1,
                'agua' => 85.50,
                'energia' => 150.00,
                'alimentacao' => 600.00,
                'gas' => 110.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'endereco_id' => 2,
                'agua' => 60.00,
                'energia' => 120.00,
                'alimentacao' => 500.00,
                'gas' => 110.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

#### Exemplo: `ResponsavelSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsavelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('responsaveis')->insert([
            [
                'id' => 1,
                'endereco_id' => 1,
                'nome' => 'Maria da Silva',
                'telefone' => '(11) 98765-4321',
                'profissao' => 'Do Lar',
                'grau_escolaridade' => 'Ensino Médio Completo',
                'salario' => 1320.00,
                'nis' => '123.45678.90-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'endereco_id' => 2,
                'nome' => 'João de Souza',
                'telefone' => '(11) 91234-5678',
                'profissao' => 'Pedreiro',
                'grau_escolaridade' => 'Ensino Fundamental Incompleto',
                'salario' => 2200.00,
                'nis' => '987.65432.10-9',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

#### Exemplo: `CriancaSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriancaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('criancas')->insert([
            [
                'id' => 1,
                'endereco_id' => 1,
                'nome' => 'Pedrinho da Silva',
                'cpf' => '111.222.333-44',
                'rg' => '55.444.333-2',
                'data_nascimento' => '2022-04-10',
                'escola' => 'Nenhuma',
                'relatos' => 'Criança calma e sociável.',
                'conselho_tutelar' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'endereco_id' => 2,
                'nome' => 'Ana Clara de Souza',
                'cpf' => '222.333.444-55',
                'rg' => '66.555.444-3',
                'data_nascimento' => '2021-08-15',
                'escola' => 'EMEI Pequeno Passo',
                'relatos' => 'Apresenta desenvolvimento motor adequado.',
                'conselho_tutelar' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

#### Exemplo: `CriancaResponsavelSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriancaResponsavelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('crianca_responsavel')->insert([
            [
                'crianca_id' => 1,
                'responsavel_id' => 1,
                'grau_parentesco' => 'Mãe',
            ],
            [
                'crianca_id' => 2,
                'responsavel_id' => 2,
                'grau_parentesco' => 'Pai',
            ]
        ]);
    }
}
```

#### Exemplo: `FichaAnamneseSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FichaAnamneseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fichas_anamnese')->insert([
            [
                'id' => 1,
                'crianca_id' => 1,
                'queixa_principal' => 'Dificuldades leves para dormir à noite.',
                'motivo_atendimento' => 'Vaga em período integral na creche.',
                'habitos' => 'Dorme cedo, usa chupeta.',
                'antecedentes_familiares' => 'Sem histórico de doenças crônicas graves.',
                'outras_informacoes' => 'Vacinada em dia.',
                'data_preenchimento' => '2026-02-10',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

#### Exemplo: `AnamneseHistoricoClinicoSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnamneseHistoricoClinicoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('anamnese_historico_clinicos')->insert([
            [
                'id' => 1,
                'ficha_anamnese_id' => 1,
                'teve_doenca' => true,
                'quais_doencas' => 'Varicela aos 1 ano de idade',
                'em_tratamento_medico' => false,
                'qual_tratamento' => null,
                'esta_gravida' => false,
                'usa_medicacao' => false,
                'quais_medicacoes' => null,
                'tem_alergia' => true,
                'quais_alergias' => 'Alergia a picada de pernilongo',
                'ja_foi_operado' => false,
                'quais_cirurgias' => null,
                'problema_visao' => false,
                'problema_audicao' => false,
                'problema_fala' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

#### Exemplo: `AnamnesePathSeeder.php`
```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnamnesePathSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('anamnese_paths')->insert([
            [
                'id' => 1,
                'ficha_anamnese_id' => 1,
                'problemas_cardiacos' => false,
                'problemas_pulmonares' => false,
                'problemas_gastrointestinais' => false,
                'problemas_neurologicos' => false,
                'problemas_ortopedicos' => false,
                'problemas_alergicos' => true,
                'manchas' => 'Nenhuma mancha relevante.',
                'observacoes' => 'Criança sem restrições alimentares severas.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```