<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ConviteController;
use Database\Seeders\CriancaDataSeeder;
use Illuminate\Support\Facades\Route;

// Rotas públicas (Acesso para visitantes / login)
Route::get('/login', [AutenticacaoController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacaoController::class, 'entrar'])->name('login.post');

// Rotas públicas de convite
Route::get('/convite/{id}', [ConviteController::class, 'mostrarConfirmacao'])->name('convite.confirmar');
Route::post('/convite/{id}/aceitar', [ConviteController::class, 'aceitar'])->name('convite.aceitar');
Route::post('/convite/{id}/recusar', [ConviteController::class, 'recusar'])->name('convite.recusar');

// Rotas privadas (Protegidas pelo middleware de autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('components.secoes.criancas.home.home');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('components.dashboard');
    })->name('dashboard');

    Route::middleware(['admin'])->prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'listar'])->name('index');
        Route::post('/convidar', [UsuarioController::class, 'salvar'])->name('convidar');
        Route::put('/{id}', [UsuarioController::class, 'atualizar'])->name('update');
        Route::put('/{id}/tornar-admin', [UsuarioController::class, 'tornarAdmin'])->name('tornarAdmin');
        Route::put('/{id}/remover-admin', [UsuarioController::class, 'removerAdmin'])->name('removerAdmin');
        Route::put('/{id}/desativar', [UsuarioController::class, 'desativar'])->name('desativar');
        Route::put('/{id}/reativar', [UsuarioController::class, 'reativar'])->name('reativar');
        Route::post('/{id}/redefinir-senha', [UsuarioController::class, 'redefinirSenha'])->name('redefinirSenha');
        Route::delete('/{id}', [UsuarioController::class, 'deletar'])->name('destroy');
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

    Route::prefix('crianca')->name('crianca.')->group(function () {
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