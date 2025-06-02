<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom', 'email', 'telephone', 'date_reabonnement', 'a_paye'
    ];

    protected $casts = [
        'date_reabonnement' => 'date',
        'a_paye' => 'boolean',
    ];
}
