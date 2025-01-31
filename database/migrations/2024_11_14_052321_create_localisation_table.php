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
        // TABLE service
        Schema::create('service', function (Blueprint $table) {
            $table->id('id_service');
            $table->string('libelle_service', 80)->unique();
            $table->timestamps();
        });
        // TABLE imputation
        Schema::create('imputation', function (Blueprint $table) {
            $table->id('id_imputation');
            $table->string('libelle_imputation', 170)->unique();
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')->references('id_service')->on('service')->onDelete('cascade');
            $table->timestamps();
        });
        // TABLE localisation
        Schema::create('localisation', function (Blueprint $table) {
            $table->id('id_localisation');
            $table->string('localisation', 255)->unique();
            $table->unsignedBigInteger('id_service');
            $table->unsignedBigInteger('id_imputation')->nullable();
            $table->foreign('id_service')->references('id_service')->on('service')->onDelete('cascade');
            $table->foreign('id_imputation')->references('id_imputation')->on('imputation')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localisation');
        Schema::dropIfExists('imputation');
        Schema::dropIfExists('service');
    }
};
