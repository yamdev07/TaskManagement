<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('clients');

        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // No
            $table->string('nom_client');
            $table->string('contact');
            $table->string('sites_relais')->nullable();
            $table->string('statut')->nullable();
            $table->string('categorie')->nullable();
            $table->date('date_reabonnement')->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
