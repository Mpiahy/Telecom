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
        DB::statement("
            CREATE VIEW view_historique_operation AS 
            SELECT 
                op.id_ligne,
                e.libelle,
                e.unite,
                e.prix_unitaire_element,
                op.quantite,
                op.debut_operation,
                op.fin_operation,
                op.commentaire,

                -- Calcul du prorata pour la quantité
                CASE 
                    WHEN EXTRACT(DAY FROM op.debut_operation) <= 30 
                    THEN ROUND(CAST(((30 - EXTRACT(DAY FROM op.debut_operation) + 1) / 30.0 * op.quantite) AS NUMERIC), 2)
                    ELSE op.quantite
                END AS quantite_prorata,

                -- Calcul du prix HT après prorata
                CASE 
                    WHEN EXTRACT(DAY FROM op.debut_operation) <= 30 
                    THEN ROUND(CAST(((30 - EXTRACT(DAY FROM op.debut_operation) + 1) / 30.0 * (op.quantite * e.prix_unitaire_element)) AS NUMERIC), 2)
                    ELSE (op.quantite * e.prix_unitaire_element)
                END AS prix_ht_prorata,

                -- Calcul du droit d'accise (+8%)
                ROUND(CAST((op.quantite * e.prix_unitaire_element) * 0.08 AS NUMERIC), 2) AS droit_accise,

                -- Calcul de la remise pied de page (-21.6%)
                ROUND(CAST((op.quantite * e.prix_unitaire_element) * 0.216 AS NUMERIC), 2) AS remise_pied_de_page,

                -- Calcul du prix HT remisé après prorata
                CASE 
                    WHEN EXTRACT(DAY FROM op.debut_operation) <= 30 
                    THEN ROUND(CAST(((30 - EXTRACT(DAY FROM op.debut_operation) + 1) / 30.0 * ((op.quantite * e.prix_unitaire_element) + 
                        ((op.quantite * e.prix_unitaire_element) * 0.08) - 
                        ((op.quantite * e.prix_unitaire_element) * 0.216))) AS NUMERIC), 2)
                    ELSE ((op.quantite * e.prix_unitaire_element) + 
                        ((op.quantite * e.prix_unitaire_element) * 0.08) - 
                        ((op.quantite * e.prix_unitaire_element) * 0.216))
                END AS prix_ht_remise_prorata

            FROM operation op
            JOIN element e ON op.id_element = e.id_element;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_historique_operation");
    }
};
