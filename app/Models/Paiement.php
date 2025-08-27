<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'client_id',
        'mois',
        'annee',
        'montant',
        'date_paiement',
        'statut',
    ];

    // Relation avec Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
