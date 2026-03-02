<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckAdmin;

Route::post('/login', [AuthController::class, 'login']);

    // Usuário acesso básico
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', action: [AuthController::class, 'logout']);
    Route::get('/clients', action: [ClientController::class, 'index']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);

    // Apenas administradores
   Route::middleware(CheckAdmin::class)->group(function () {
        Route::post('/logout', action: [AuthController::class, 'logout']);
        Route::post('/clients', action: [ClientController::class, 'store']);

        // Rotas para transações
        Route::prefix('transactions/{client}')->group(function () {
            Route::post('/credit', [TransactionController::class, 'credit']);
            Route::post('/debit',  [TransactionController::class, 'debit']);
         });
    });
});
