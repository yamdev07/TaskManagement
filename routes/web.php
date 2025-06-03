<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/reabonnement', [ClientController::class, 'aReabonnement'])->name('clients.reabonnement');
Route::get('/clients/depasses', [ClientController::class, 'depasses'])->name('clients.depasses');
Route::get('/clients/payes', [ClientController::class, 'clientsPayes'])->name('clients.payes');
Route::get('/clients/nonpayes', [ClientController::class, 'nonPayes'])->name('clients.nonpayes');
Route::get('/clients/notifier', [ClientController::class, 'envoyerNotifications'])->name('clients.notifier');
Route::get('/clients/envoyer-notifications', [ClientController::class, 'envoyerNotifications'])->name('clients.envoyerNotifications');

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
