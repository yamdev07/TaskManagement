<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
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
    ];

    // --- Relation avec Paiement ---
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
