<?php

use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ConviteController;
use App\Http\Controllers\CriancaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\RelatoController;
use Illuminate\Support\Facades\Route;

// Rotas públicas (Acesso para visitantes / login)
Route::get('/login', [AutenticacaoController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacaoController::class, 'entrar'])->name('login.post');

// Rotas públicas de convite
Route::get('/convite/{id}', [ConviteController::class, 'mostrarConfirmacao'])->name('convite.confirmar');
Route::post('/convite/{id}/aceitar', [ConviteController::class, 'aceitar'])->name('convite.aceitar');
Route::post('/convite/{id}/recusar', [ConviteController::class, 'recusar'])->name('convite.recusar');

// Rotas públicas de redefinição de senha (esqueci minha senha)
Route::get('/redefinir-senha', [AutenticacaoController::class, 'mostrarRedefinirSenha'])->name('senha.redefinir');
Route::post('/redefinir-senha', [AutenticacaoController::class, 'solicitarRedefinicaoSenha'])->name('senha.solicitar');

// Rotas privadas (Protegidas pelo middleware de autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('components.secoes.criancas.home.home');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('components.dashboard');
    })->name('dashboard');

    Route::middleware(['admin'])->prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {
        Route::get('/', [AdminUsuarioController::class, 'listar'])->name('index');
        Route::post('/convidar', [AdminUsuarioController::class, 'convidar'])->name('convidar');
        Route::put('/{id}', [AdminUsuarioController::class, 'atualizar'])->name('update');
        Route::put('/{id}/tornar-admin', [AdminUsuarioController::class, 'tornarAdmin'])->name('tornarAdmin');
        Route::put('/{id}/remover-admin', [AdminUsuarioController::class, 'removerAdmin'])->name('removerAdmin');
        Route::put('/{id}/desativar', [AdminUsuarioController::class, 'desativar'])->name('desativar');
        Route::put('/{id}/reativar', [AdminUsuarioController::class, 'reativar'])->name('reativar');
        Route::post('/{id}/redefinir-senha', [AdminUsuarioController::class, 'redefinirSenha'])->name('redefinirSenha');
        Route::delete('/{id}', [AdminUsuarioController::class, 'deletar'])->name('destroy');
    });

    Route::post('/logout', [AutenticacaoController::class, 'sair'])->name('logout');

    Route::get('/meu-perfil', [AutenticacaoController::class, 'usuarioAutenticado'])->name('profile');
    Route::put('/meu-perfil', [AutenticacaoController::class, 'atualizarPerfil'])->name('profile.update');

    Route::prefix('usuario')->name('usuario.')->group(function () {
        Route::get('/', [UsuarioController::class, 'listar'])->name('index');
        Route::get('/{id}', [UsuarioController::class, 'buscarPorId'])->name('show');
        Route::post('/', [UsuarioController::class, 'salvar'])->name('store');
        Route::put('/{id}', [UsuarioController::class, 'atualizar'])->name('update');
        Route::delete('/{id}', [UsuarioController::class, 'deletar'])->name('destroy');
    });

    Route::prefix('matricula')->name('matricula.')->group(function () {
        Route::redirect('/', '/matricula/crianca');
        Route::get('/crianca', [MatriculaController::class, 'mostrarCrianca'])->name('crianca');
        Route::post('/crianca', [MatriculaController::class, 'salvarCrianca'])->name('crianca.salvar');
        Route::get('/crianca/foto', [MatriculaController::class, 'fotoRascunho'])->name('crianca.foto');
        Route::get('/responsaveis', [MatriculaController::class, 'mostrarResponsaveis'])->name('responsaveis');
        Route::post('/responsaveis', [MatriculaController::class, 'salvarResponsaveis'])->name('responsaveis.salvar');
        Route::get('/endereco', [MatriculaController::class, 'mostrarEndereco'])->name('endereco');
        Route::post('/endereco', [MatriculaController::class, 'salvarEndereco'])->name('endereco.salvar');
        Route::get('/saude', [MatriculaController::class, 'mostrarSaude'])->name('saude');
        Route::post('/saude', [MatriculaController::class, 'salvarSaude'])->name('saude.salvar');
        Route::get('/autorizacoes', [MatriculaController::class, 'mostrarAutorizacoes'])->name('autorizacoes');
        Route::post('/autorizacoes', [MatriculaController::class, 'salvarAutorizacoes'])->name('autorizacoes.salvar');
    });

    Route::prefix('relatos')->name('relatos.')->group(function () {
        Route::get('/', [RelatoController::class, 'index'])->name('index');
        Route::get('/criar', [RelatoController::class, 'create'])->name('create');
        Route::post('/', [RelatoController::class, 'store'])->name('store');
        Route::get('/{id}', [RelatoController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [RelatoController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{id}', [RelatoController::class, 'update'])->name('update');
        Route::delete('/{id}', [RelatoController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('crianca')->name('crianca.')->group(function () {
        Route::redirect('/cadastro', '/matricula/crianca');
        Route::get('/menu/{id}', function ($id) {
            $crianca = app(\App\Services\CriancaTelaService::class)->dados($id);

            abort_if(!$crianca, 404, 'Criança não encontrada');

            return view('components.menu.menuSecoesCriancas.menuCrianca', compact('crianca'));
        })->name('menu');

        Route::patch('/{crianca}/nome', [CriancaController::class, 'atualizarNome'])->name('nome');
        Route::patch('/{crianca}/status', [CriancaController::class, 'atualizarStatus'])->name('status');
        Route::delete('/{crianca}', [CriancaController::class, 'excluir'])->name('excluir');
        Route::put('/{crianca}/dados', [CriancaController::class, 'atualizarDados'])->name('dados');
        Route::put('/{crianca}/responsaveis/{responsavel}', [CriancaController::class, 'atualizarResponsavel'])->name('responsavel.atualizar');
        Route::put('/{crianca}/ambiente', [CriancaController::class, 'atualizarAmbiente'])->name('ambiente');
        Route::patch('/{crianca}/autorizacoes/permissoes', [CriancaController::class, 'atualizarPermissoes'])->name('permissoes');
        Route::post('/{crianca}/autorizacoes/pessoas', [CriancaController::class, 'criarPessoaAutorizada'])->name('pessoas.criar');
        Route::delete('/{crianca}/autorizacoes/pessoas/{pessoa}', [CriancaController::class, 'excluirPessoaAutorizada'])->name('pessoas.excluir');
        Route::post('/{crianca}/assinatura', [CriancaController::class, 'atualizarAssinatura'])->name('assinatura');
        Route::get('/{crianca}/foto', [CriancaController::class, 'foto'])->name('foto');
        Route::post('/{crianca}/foto', [CriancaController::class, 'atualizarFoto'])->name('foto.atualizar');
        Route::delete('/{crianca}/foto', [CriancaController::class, 'excluirFoto'])->name('foto.excluir');

        $secoes = ['saude', 'responsaveis', 'autorizacoes', 'relatos', 'matricula'];

        foreach ($secoes as $secao) {
            Route::get("/{id}/{$secao}", function ($id) use ($secao) {
                return view("components.secoes.criancas.{$secao}.{$secao}", [
                    'criancaId' => $id,
                ]);
            })->whereNumber('id')->name($secao);
        }
    });
});