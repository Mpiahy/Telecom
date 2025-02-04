<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Création de la table 'operation'
        Schema::create('operation', function (Blueprint $table) {
            $table->id('id_operation');
            $table->unsignedBigInteger('id_ligne');
            $table->unsignedBigInteger('id_element');
            $table->integer('quantite')->default(0);
            $table->date('debut_operation');
            $table->date('fin_operation'); // Sera rempli automatiquement par le trigger
            $table->text('commentaire')->nullable();

            // Clés étrangères
            $table->foreign('id_ligne')
                ->references('id_ligne')
                ->on('ligne')
                ->onDelete('cascade');

            $table->foreign('id_element')
                ->references('id_element')
                ->on('element')
                ->onDelete('cascade');

            $table->timestamps();
        });

        // Création de la fonction qui calcule le dernier jour du mois
        DB::statement("
            CREATE OR REPLACE FUNCTION get_last_day_of_month(date_value DATE) 
            RETURNS DATE AS $$
            BEGIN
                RETURN (date_trunc('month', date_value) + interval '1 month' - interval '1 day')::date;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Création de la fonction trigger qui remplit 'fin_operation'
        DB::statement("
            CREATE OR REPLACE FUNCTION set_fin_operation()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.fin_operation := get_last_day_of_month(NEW.debut_operation);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Création du trigger qui applique la mise à jour automatique
        DB::statement("
            CREATE TRIGGER tr_set_fin_operation
            BEFORE INSERT OR UPDATE OF debut_operation
            ON operation
            FOR EACH ROW
            EXECUTE FUNCTION set_fin_operation();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression du trigger
        DB::statement("DROP TRIGGER IF EXISTS tr_set_fin_operation ON operation");

        // Suppression de la fonction de trigger
        DB::statement("DROP FUNCTION IF EXISTS set_fin_operation");

        // Suppression de la fonction de calcul de fin de mois
        DB::statement("DROP FUNCTION IF EXISTS get_last_day_of_month");

        // Suppression de la table 'operation'
        Schema::dropIfExists('operation');
    }
};
