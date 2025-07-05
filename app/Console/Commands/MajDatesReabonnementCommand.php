<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use Carbon\Carbon;

class MajDatesReabonnementCommand extends Command
{
    protected $signature = 'clients:mettre-a-jour-reabonnement';
    protected $description = 'Met à jour automatiquement les dates de réabonnement chaque mois';

    public function handle()
    {
        $mois = now()->month;
        $annee = now()->year;

        $clients = Client::whereNotNull('jour_reabonnement')->get();
        $total = 0;

        foreach ($clients as $client) {
            try {
                $date = Carbon::create($annee, $mois, $client->jour_reabonnement);

                if ($date->isPast()) {
                    $date->addMonth();
                }

                $client->date_reabonnement = $date->toDateString();
                $client->save();

                $total++;
            } catch (\Exception $e) {
                $this->warn("Jour invalide pour client ID {$client->id} : {$client->jour_reabonnement}");
            }
        }

        $this->info("Mise à jour terminée : $total clients mis à jour.");
        return 0;
    }
}
