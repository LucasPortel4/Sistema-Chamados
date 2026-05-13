<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminChamadoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\ChamadoPublicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChamadoPublicoController::class, 'create'])->name('home');
Route::get('/avaliacoes', [AvaliacaoController::class, 'index'])->name('avaliacao.index');
Route::get('/chamado/{id}/avaliar', [AvaliacaoController::class, 'create'])->whereNumber('id')->name('avaliacao.create');
Route::post('/chamado/{id}/avaliar', [AvaliacaoController::class, 'store'])->whereNumber('id')->name('avaliacao.store');

Route::prefix('chamado')->group(function () {
    Route::get('/criar', [ChamadoPublicoController::class, 'create'])->name('chamado.criar');
    Route::post('/criar', [ChamadoPublicoController::class, 'store'])->name('chamado.store');
    Route::get('/sucesso/{id}', [ChamadoPublicoController::class, 'sucesso'])->name('chamado.sucesso');
    Route::get('/consultar', [ChamadoPublicoController::class, 'consultar'])->name('chamado.consultar');
    Route::get('/status/{id}', [ChamadoPublicoController::class, 'status'])->whereNumber('id')->name('chamado.status');
    Route::post('/buscar', [ChamadoPublicoController::class, 'buscar'])->name('chamado.buscar');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminChamadoController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/chamados', [AdminChamadoController::class, 'index'])->name('admin.chamados.index');
        Route::get('/chamados/{id}', [AdminChamadoController::class, 'show'])->name('admin.chamados.show');
        Route::post('/chamados/{id}/status', [AdminChamadoController::class, 'atualizarStatus'])->name('admin.chamados.status');
    });
});
