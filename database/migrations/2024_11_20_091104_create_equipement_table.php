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
        // TABLE statut_equipement
        Schema::create('statut_equipement', function (Blueprint $table) {
            $table->unsignedBigInteger('id_statut_equipement')->primary(); // Pas d'auto-incrément
            $table->string('statut_equipement', 20);
            $table->timestamps();
        });
        
        // TABLE type_equipement
        Schema::create('type_equipement', function (Blueprint $table) {
            $table->unsignedBigInteger('id_type_equipement')->primary(); // Pas d'auto-incrément
            $table->string('type_equipement', 20);
            $table->timestamps();
        });
        
        // TABLE marque
        Schema::create('marque', function (Blueprint $table) {
            $table->unsignedBigInteger('id_marque')->primary(); // Pas d'auto-incrément
            $table->string('marque', 20);
            $table->timestamps();
        });
        
        // TABLE modele
        Schema::create('modele', function (Blueprint $table) {
            $table->unsignedBigInteger('id_modele')->primary(); // Pas d'auto-incrément
            $table->string('nom_modele', 30);
            $table->unsignedBigInteger('id_marque');

            // Clé étrangère
            $table->foreign('id_marque')
                ->references('id_marque')
                ->on('marque')
                ->onDelete('cascade');

            $table->timestamps();
        });
        
        // TABLE equipement
        Schema::create('equipement', function (Blueprint $table) {
            $table->id('id_equipement');
            $table->string('imei', 50)->nullable();
            $table->string('serial_number', 50)->nullable();
            $table->boolean('enrole')->default(false);
            $table->unsignedBigInteger('id_type_equipement');
            $table->unsignedBigInteger('id_modele');
            $table->unsignedBigInteger('id_statut_equipement');

            // Clés étrangères
            $table->foreign('id_type_equipement')
                ->references('id_type_equipement')
                ->on('type_equipement')
                ->onDelete('cascade');

            $table->foreign('id_modele')
                ->references('id_modele')
                ->on('modele')
                ->onDelete('cascade');

            $table->foreign('id_statut_equipement')
                ->references('id_statut_equipement')
                ->on('statut_equipement')
                ->onDelete('cascade');

            $table->timestamps();
        });
        
        // TABLE id_counters
        Schema::create('id_counters', function (Blueprint $table) {
            $table->string('entity'); // Pour stocker le type d'entité : 'marque', 'modele', etc.
            $table->unsignedBigInteger('type_or_marque_id'); // Peut représenter id_type_equipement ou id_marque selon l'entité
            $table->unsignedBigInteger('last_id')->default(0); // Le dernier ID généré pour cette entité/type
            $table->timestamps();

            $table->primary(['entity', 'type_or_marque_id']); // Clé composite pour éviter les doublons sur une même entité et type/marque
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipement');
        Schema::dropIfExists('statut_equipement');
        Schema::dropIfExists('type_equipement');
        Schema::dropIfExists('modele');
        Schema::dropIfExists('marque');
        Schema::dropIfExists('id_counters');
    }
};
