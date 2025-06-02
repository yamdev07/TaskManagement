<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('lieu')->nullable()->after('telephone'); 
            // nullable si tu veux autoriser les clients sans lieu renseigné
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('lieu');
        });
    }

};
