<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use Database\Seeders\CriancaDataSeeder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('components.secoes.criancas.home.home');
})->name('home');

Route::get('/a', function () {
    return view('components.usuario.login');
})->name('home');

Route::get('/b', function () {
    return view('components.dashboard');
})->name('home');

Route::get('/c', function () {
    return view('components.usuario.perfil');
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