<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->integer('mois'); // ex: 1 = Janvier, 8 = Août
            $table->integer('annee'); // ex: 2025
            $table->decimal('montant', 10, 2)->nullable(); // montant payé
            $table->date('date_paiement')->nullable(); // quand le paiement a été fait
            $table->enum('statut', ['payé', 'non'])->default('non'); // état du paiement
            $table->timestamps();

            // Empêcher qu’un client ait 2 paiements pour le même mois/année
            $table->unique(['client_id', 'mois', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
