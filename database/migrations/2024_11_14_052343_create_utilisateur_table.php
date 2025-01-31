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
        // TABLE type_utilisateur
        Schema::create('type_utilisateur', function (Blueprint $table) {
            $table->id('id_type_utilisateur');
            $table->string('type_utilisateur', 20);
            $table->timestamps();
        });
        // TABLE fonction
        Schema::create('fonction', function (Blueprint $table) {
            $table->id('id_fonction');
            $table->string('fonction', 50);
            $table->timestamps();
        });
        // TABLE utilisateur
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->id('id_utilisateur');
            $table->unsignedBigInteger('matricule')->nullable();
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('login', 40)->nullable();
            $table->unsignedBigInteger('id_type_utilisateur');
            $table->unsignedBigInteger('id_fonction')->nullable();
            $table->unsignedBigInteger('id_localisation')->nullable();
            $table->foreign('id_type_utilisateur')->references('id_type_utilisateur')->on('type_utilisateur')->onDelete('cascade');
            $table->foreign('id_fonction')->references('id_fonction')->on('fonction')->onDelete('cascade');
            $table->foreign('id_localisation')->references('id_localisation')->on('localisation')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
        Schema::dropIfExists('type_utilisateur');
        Schema::dropIfExists('fonction');
    }
};
