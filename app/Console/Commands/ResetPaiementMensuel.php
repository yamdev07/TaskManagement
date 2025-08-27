<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;

class ResetPaiementMensuel extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'clients:reset-paiement';

    /**
     * Description de la commande
     *
     * @var string
     */
    protected $description = 'Réinitialise le champ a_paye de tous les clients à 0 au début de chaque mois';

    /**
     * Exécution de la commande
     */
    public function handle()
    {
        Client::query()->update(['a_paye' => 0]);

        $this->info('Tous les paiements ont été remis à 0 avec succès.');
    }
}
