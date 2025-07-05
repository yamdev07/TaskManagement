<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Définir les commandes planifiées.
     */
    protected function schedule(Schedule $schedule)
    {
        // Exécuter la commande chaque 1er du mois à minuit
        $schedule->command('clients:mettre-a-jour-reabonnement')->monthlyOn(1, '00:00');
    }

    /**
     * Enregistrer les commandes Artisan.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
