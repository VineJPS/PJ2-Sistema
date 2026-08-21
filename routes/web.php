<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use Illuminate\Support\Facades\Route;

Route::prefix('usuario')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UsuarioController::class, 'listar']);
    Route::get('/{id}', [UsuarioController::class, 'buscarPorId']);
    Route::post('/', [UsuarioController::class, 'salvar']);
    Route::put('/{id}', [UsuarioController::class, 'atualizar']);
    Route::delete('/{id}', [UsuarioController::class, 'deletar']);
});


// Rotas públicas (Acesso sem estar logado)
Route::post('/login', [AutenticacaoController::class, 'entrar']);

// Rotas protegidas (Exigem sessão ativa do Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AutenticacaoController::class, 'sair']);
    Route::get('/meu-perfil', [AutenticacaoController::class, 'usuarioAutenticado']);
});