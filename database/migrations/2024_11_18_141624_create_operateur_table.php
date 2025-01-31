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
        // TABLE operateur
        Schema::create('operateur', function (Blueprint $table) {
            $table->integer('id_operateur')->primary()->unsigned();
            $table->string('nom_operateur', 50);
            $table->timestamps();
        });
        // TABLE contact_operateur (possibilité 1 operateur = * contact)
        Schema::create('contact_operateur', function (Blueprint $table) {
            $table->id('id_contact');
            $table->string('nom', 100);
            $table->string('email', 50);
            $table->unsignedInteger('id_operateur');
            $table->foreign('id_operateur')->references('id_operateur')->on('operateur')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_operateur');
        Schema::dropIfExists('operateur');
    }
};
