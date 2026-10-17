<?php

use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ConviteController;
use App\Http\Controllers\MatriculaController;
use Database\Seeders\CriancaDataSeeder;
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
        Route::get('/responsaveis', [MatriculaController::class, 'mostrarResponsaveis'])->name('responsaveis');
        Route::post('/responsaveis', [MatriculaController::class, 'salvarResponsaveis'])->name('responsaveis.salvar');
        Route::get('/endereco', [MatriculaController::class, 'mostrarEndereco'])->name('endereco');
        Route::post('/endereco', [MatriculaController::class, 'salvarEndereco'])->name('endereco.salvar');
        Route::get('/saude', [MatriculaController::class, 'mostrarSaude'])->name('saude');
        Route::post('/saude', [MatriculaController::class, 'salvarSaude'])->name('saude.salvar');
        Route::get('/autorizacoes', [MatriculaController::class, 'mostrarAutorizacoes'])->name('autorizacoes');
        Route::post('/autorizacoes', [MatriculaController::class, 'salvarAutorizacoes'])->name('autorizacoes.salvar');
    });

    Route::prefix('crianca')->name('crianca.')->group(function () {
        Route::redirect('/cadastro', '/matricula/crianca');
        Route::get('/menu/{id}', function ($id) {
            $crianca = CriancaDataSeeder::getCriancaById($id);

            abort_if(!$crianca, 404, 'Criança não encontrada');

            return view('components.menu.menuSecoesCriancas.menuCrianca', compact('crianca'));
        })->name('menu');

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