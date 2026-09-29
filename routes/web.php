<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use Database\Seeders\CriancaDataSeeder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('components.secoes.criancas.home.home');
})->name('home');

Route::post('/login', [AutenticacaoController::class, 'entrar'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AutenticacaoController::class, 'sair'])->name('logout');
    Route::get('/meu-perfil', [AutenticacaoController::class, 'usuarioAutenticado'])->name('profile');

    Route::prefix('usuario')->name('usuario.')->group(function () {
        Route::get('/', [UsuarioController::class, 'listar'])->name('index');
        Route::get('/{id}', [UsuarioController::class, 'buscarPorId'])->name('show');
        Route::post('/', [UsuarioController::class, 'salvar'])->name('store');
        Route::put('/{id}', [UsuarioController::class, 'atualizar'])->name('update');
        Route::delete('/{id}', [UsuarioController::class, 'deletar'])->name('destroy');
    });
});

Route::prefix('crianca')->name('crianca.')->group(function () {
    
    Route::get('/menu/{id}', function ($id) {
        $crianca = CriancaDataSeeder::getCriancaById($id);

        if (!$crianca) {
            abort(404, 'Criança não encontrada');
        }

        return view('components.menu.menuSecoesCriancas.menuCrianca', compact('crianca'));
    })->name('menu');

    Route::get('/{id}/saude', function ($id) {
        return view('components.secoes.criancas.saude.saude', ['criancaId' => $id]);
    })->name('saude');

    Route::get('/{id}/responsaveis', function ($id) {
        return view('components.secoes.criancas.responsaveis.responsaveis', ['criancaId' => $id]);
    })->name('responsaveis');

    Route::get('/{id}/autorizacoes', function ($id) {
        return view('components.secoes.criancas.autorizacoes.autorizacoes', ['criancaId' => $id]);
    })->name('autorizacoes');

    Route::get('/{id}/relatos', function ($id) {
        return view('components.secoes.criancas.relatos.relatos', ['criancaId' => $id]);
    })->name('relatos');

    Route::get('/{id}/matricula', function ($id) {
        return view('components.secoes.criancas.matricula.matricula', ['criancaId' => $id]);
    })->name('matricula');
});
