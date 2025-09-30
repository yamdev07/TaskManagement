<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_client',
        'contact',
        'sites_relais',
        'statut',
        'categorie',
        'date_reabonnement',
        'montant',
        'a_paye',
    ];

    protected $casts = [
        'a_paye' => 'boolean',
        'date_reabonnement' => 'date', // pour utiliser Carbon facilement
    ];

    // --- Relation avec Paiement ---
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // --- Accessor pour le prochain mois dû ---
    public function getProchainMoisDuAttribute()
    {
        $dernierPaiement = $this->paiements()->where('statut', true)->latest('annee', 'mois')->first();

        if ($dernierPaiement) {
            // Crée une date avec le mois et l'année du dernier paiement et ajoute 1 mois
            return Carbon::create($dernierPaiement->annee, $dernierPaiement->mois, 1)
                         ->addMonth()
                         ->format('m/Y');
        }

        return 'Non payé';
    }
}
