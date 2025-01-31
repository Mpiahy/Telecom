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
        // TABLE statut_ligne
        Schema::create('statut_ligne', function (Blueprint $table) {
            $table->unsignedBigInteger('id_statut_ligne')->primary(); // Pas d'auto-incrément
            $table->string('statut_ligne', 20);
            $table->timestamps();
        });
        
        // TABLE type_ligne
        Schema::create('type_ligne', function (Blueprint $table) {
            $table->unsignedBigInteger('id_type_ligne')->primary(); // Pas d'auto-incrément
            $table->string('type_ligne', 50);
            $table->timestamps();
        });

        // TABLE ligne
        Schema::create('ligne', function (Blueprint $table) {
            $table->id('id_ligne');
            $table->string('num_ligne', 30)->nullable();
            $table->string('num_sim', 30);
            $table->unsignedBigInteger('id_forfait');
            $table->unsignedBigInteger('id_statut_ligne');
            $table->unsignedBigInteger('id_type_ligne');
            $table->unsignedBigInteger('id_operateur');

            // Clés étrangères
            $table->foreign('id_forfait')
                ->references('id_forfait')
                ->on('forfait')
                ->onDelete('cascade');

            $table->foreign('id_statut_ligne')
                ->references('id_statut_ligne')
                ->on('statut_ligne')
                ->onDelete('cascade');

            $table->foreign('id_type_ligne')
                ->references('id_type_ligne')
                ->on('type_ligne')
                ->onDelete('cascade');
            
            $table->foreign('id_operateur')
                ->references('id_operateur')
                ->on('operateur')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne');
        Schema::dropIfExists('statut_ligne');
        Schema::dropIfExists('type_ligne');
    }
};
