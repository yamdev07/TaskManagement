<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Groupe des routes pour les clients
Route::prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/create', [ClientController::class, 'create'])->name('create');
    Route::post('/', [ClientController::class, 'store'])->name('store');
    Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
    Route::put('/{client}', [ClientController::class, 'update'])->name('update');

    // Filtres et vues spécifiques
    Route::get('/reabonnement', [ClientController::class, 'aReabonnement'])->name('reabonnement');
    Route::get('/depasses', [ClientController::class, 'depasses'])->name('depasses');
    Route::get('/payes', [ClientController::class, 'clientsPayes'])->name('payes');
    Route::get('/nonpayes', [ClientController::class, 'nonPayes'])->name('nonpayes');

    // Notifications
    Route::get('/envoyer-notifications', [ClientController::class, 'envoyerNotifications'])->name('envoyerNotifications');
    // Optionnel : supprime si non nécessaire
    Route::get('/notifier', [ClientController::class, 'envoyerNotifications'])->name('notifier');
});
