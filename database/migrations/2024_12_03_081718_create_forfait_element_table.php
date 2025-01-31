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
        // TABLE type_forfait
        Schema::create('type_forfait', function (Blueprint $table) {
            $table->id('id_type_forfait');
            $table->string('type_forfait', 50);
            $table->timestamps();
        });
        
        // TABLE element
        Schema::create('element', function (Blueprint $table) {
            $table->id('id_element');
            $table->string('libelle', 50);
            $table->string('unite', 50);
            $table->double('prix_unitaire_element');
            $table->timestamps();
        });

        // TABLE forfait
        Schema::create('forfait', function (Blueprint $table) {
            $table->id('id_forfait');
            $table->string('nom_forfait', 50);
            $table->unsignedBigInteger('id_type_forfait');
            $table->unsignedBigInteger('id_operateur');
            
            // Clés étrangères
            $table->foreign('id_type_forfait')
                ->references('id_type_forfait')
                ->on('type_forfait')
                ->onDelete('cascade');

            $table->foreign('id_operateur')
                ->references('id_operateur')
                ->on('operateur')
                ->onDelete('cascade');
            
            $table->timestamps();
        });

        // TABLE forfait_element
        Schema::create('forfait_element', function (Blueprint $table) {
            $table->unsignedBigInteger('id_element');
            $table->unsignedBigInteger('id_forfait');
            $table->integer('quantite')->default(0);

            $table->primary(['id_element', 'id_forfait']);

            $table->foreign('id_element')
                ->references('id_element')
                ->on('element')
                ->onDelete('cascade');

            $table->foreign('id_forfait')
                ->references('id_forfait')
                ->on('forfait')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forfait_element');
        Schema::dropIfExists('forfait');
        Schema::dropIfExists('element');
        Schema::dropIfExists('type_forfait');
    }
};
