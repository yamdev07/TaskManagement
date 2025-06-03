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
];


  
}
