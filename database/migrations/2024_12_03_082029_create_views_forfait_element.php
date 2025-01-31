<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE VIEW view_element_prix AS
            SELECT
                fe.id_forfait,
                e.id_element,
                e.libelle,
                fe.quantite,
                e.unite,
                e.prix_unitaire_element,
                (fe.quantite * e.prix_unitaire_element) AS prix_total_element
            FROM
                forfait_element fe
            JOIN
                element e ON fe.id_element = e.id_element;
        ');

        DB::statement('
        CREATE OR REPLACE VIEW view_forfait_prix AS
        SELECT
            sub.id_forfait,
            sub.nom_forfait,
            sub.prix_forfait_ht_non_remise,
            sub.droit_d_accise,
            sub.remise_pied_de_page,
            (sub.prix_forfait_ht_non_remise + sub.droit_d_accise - sub.remise_pied_de_page) AS prix_forfait_ht,
            ROUND(CAST((sub.prix_forfait_ht_non_remise + sub.droit_d_accise - sub.remise_pied_de_page) / 30 AS NUMERIC), 2) AS prix_jour -- Montant journalier
        FROM (
            SELECT
                f.id_forfait,
                f.nom_forfait,
                SUM(fe.quantite * e.prix_unitaire_element) AS prix_forfait_ht_non_remise,
                SUM(fe.quantite * e.prix_unitaire_element) * 0.08 AS droit_d_accise,
                SUM(fe.quantite * e.prix_unitaire_element) * 0.216 AS remise_pied_de_page
            FROM
                forfait f
            JOIN
                forfait_element fe ON f.id_forfait = fe.id_forfait
            JOIN
                element e ON fe.id_element = e.id_element
            GROUP BY
                f.id_forfait, f.nom_forfait
        ) sub;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_forfait_prix CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_element_prix CASCADE;');
    }
};
