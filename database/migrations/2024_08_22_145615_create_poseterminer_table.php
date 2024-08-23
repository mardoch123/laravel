<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('poseterminer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mission_id');
            $table->string('photo_emplacement_evaporateur');
            $table->string('photo_numero_serie_evaporateur');
            $table->string('photo_raccordement_electrique');
            $table->string('photo_emplacement_condensateur');
            $table->string('photo_numero_serie_condensateur');
            $table->timestamps();

            // Définir les contraintes de clé étrangère
            $table->foreign('mission_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poseterminer');
    }
};
