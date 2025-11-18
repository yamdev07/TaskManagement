<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil publique
Route::get('/', fn() => view('welcome'));

// Routes protégées par auth
Route::middleware(['auth'])->group(function () {

    // Dashboard redirection
    Route::get('/dashboard', fn() => redirect()->route('clients.index'))->name('dashboard');

    // Routes clients
    Route::prefix('clients')->name('clients.')->group(function () {

        // --- COMMERCIAL & ADMIN : lecture + ajouter ---
        Route::middleware(['role:commercial,admin'])->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');

            Route::get('/payes', [ClientController::class, 'clientsPayes'])->name('payes');
            Route::get('/nonpayes', [ClientController::class, 'nonPayes'])->name('nonpayes');
            Route::get('/actifs', [ClientController::class, 'clientsActifs'])->name('actifs');
            Route::get('/suspendus', [ClientController::class, 'clientsSuspendus'])->name('suspendus');
            Route::get('/reabonnement', [ClientController::class, 'aReabonnement'])->name('reabonnement');
            Route::get('/depasses', [ClientController::class, 'depasses'])->name('depasses');
            Route::get('/dans3jours', [ClientController::class, 'dans3Jours'])->name('dans3jours');
        });

        // --- ADMIN : toutes les actions CRUD et spéciales ---
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');

            Route::patch('/{client}/reconnecter', [ClientController::class, 'reconnecter'])->name('reconnecter');
            Route::patch('/{client}/suspendre', [ClientController::class, 'suspendre'])->name('suspendre');
            Route::post('/{client}/deconnecter', [ClientController::class, 'deconnecter'])->name('deconnecter');
            Route::post('/{client}/relancer', [ClientController::class, 'relancerViaWhatsApp'])->name('relancer');
            Route::patch('/{client}/marquer-paye', [ClientController::class, 'marquerCommePaye'])->name('marquer-paye');
            Route::post('/{client}/reactiver', [ClientController::class, 'reactiver'])->name('reactiver');

            Route::post('/export', [ClientController::class, 'export'])->name('export');
            Route::get('/export/pdf', [ClientController::class, 'exportPdf'])->name('exportPdf');

            Route::get('/ajax/list', [ClientController::class, 'ajaxList'])->name('ajaxList');
            Route::get('/envoyer-notifications', [ClientController::class, 'envoyerNotifications'])->name('envoyerNotifications');
            Route::get('/notifier', [ClientController::class, 'envoyerNotifications'])->name('notifier');
        });

        // --- Lecture individuelle (tous) doit être à la fin ---
        Route::middleware(['role:commercial,admin'])->get('/{client}', [ClientController::class, 'show'])->name('show');
    });

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Laravel Breeze/Fortify
require __DIR__.'/auth.php';
