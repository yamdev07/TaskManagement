<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Page d'accueil publique (si tu veux la garder)
Route::get('/', function () {
    return view('welcome');
});


// Routes protégées par auth
Route::middleware(['auth'])->group(function () {

    // Redirection vers dashboard ou clients
    Route::get('/dashboard', function () {
        return redirect()->route('clients.index');
    })->name('dashboard');

    // Groupe des routes pour les clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');

        Route::patch('/{client}/reconnecter', [ClientController::class, 'reconnecter'])->name('reconnecter');
        Route::post('/{client}/deconnecter', [ClientController::class, 'deconnecter'])->name('deconnecter');
        Route::get('/ajax/list', [ClientController::class, 'ajaxList'])->name('ajaxList');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        Route::post('/{client}/relancer', [ClientController::class, 'relancerViaWhatsApp'])->name('relancer');

        Route::get('/reabonnement', [ClientController::class, 'aReabonnement'])->name('reabonnement');
        Route::get('/depasses', [ClientController::class, 'depasses'])->name('depasses');
        Route::get('/payes', [ClientController::class, 'clientsPayes'])->name('payes');
        Route::get('/nonpayes', [ClientController::class, 'nonPayes'])->name('nonpayes');
        Route::get('/actifs', [ClientController::class, 'clientsActifs'])->name('actifs');
        Route::get('/suspendus', [ClientController::class, 'clientsSuspendus'])->name('suspendus');
        Route::get('/dans3jours', [ClientController::class, 'dans3Jours'])->name('dans3jours');

        Route::get('/envoyer-notifications', [ClientController::class, 'envoyerNotifications'])->name('envoyerNotifications');
        Route::get('/notifier', [ClientController::class, 'envoyerNotifications'])->name('notifier');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php'; // ✅ indispensable pour que les routes de login/register fonctionnent
