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
            CREATE OR REPLACE VIEW view_ligne_details AS
            SELECT
                ligne.id_ligne,
                ligne.num_ligne,
                ligne.num_sim,
                ligne.id_forfait,
                ligne.id_statut_ligne,
                ligne.id_type_ligne,
                ligne.id_operateur,
                recent_affectation.id_affectation,
                recent_affectation.id_utilisateur
            FROM
                ligne
                LEFT JOIN (
                    SELECT DISTINCT ON (id_ligne) 
                        id_ligne,
                        id_affectation,
                        id_utilisateur,
                        debut_affectation
                    FROM affectation
                    ORDER BY id_ligne, debut_affectation DESC, id_affectation DESC
                ) AS recent_affectation
            ON ligne.id_ligne = recent_affectation.id_ligne;
        ');
        DB::statement('
            CREATE OR REPLACE VIEW view_ligne_big_details AS
            SELECT 
                vld.id_ligne,
                vld.num_ligne,
                vld.num_sim,
                vld.id_forfait,
                forfait.nom_forfait,
                vld.id_statut_ligne,
                statut_ligne.statut_ligne,
                vld.id_type_ligne,
                type_ligne.type_ligne,
                vld.id_operateur,
                operateur.nom_operateur,
                contact_operateur.email AS contact_email,
                vld.id_utilisateur,
                utilisateur.login,
                localisation.localisation,
                vfp.prix_forfait_ht,
                vld.id_affectation,
                affectation.debut_affectation,
                affectation.fin_affectation
            FROM 
                view_ligne_details vld
            LEFT JOIN forfait ON vld.id_forfait = forfait.id_forfait
            LEFT JOIN statut_ligne ON vld.id_statut_ligne = statut_ligne.id_statut_ligne
            LEFT JOIN type_ligne ON vld.id_type_ligne = type_ligne.id_type_ligne
            LEFT JOIN operateur ON vld.id_operateur = operateur.id_operateur
            LEFT JOIN contact_operateur ON vld.id_operateur = contact_operateur.id_operateur
            LEFT JOIN view_forfait_prix vfp ON vld.id_forfait = vfp.id_forfait
            LEFT JOIN utilisateur ON vld.id_utilisateur = utilisateur.id_utilisateur
            LEFT JOIN localisation ON utilisateur.id_localisation = localisation.id_localisation
            LEFT JOIN affectation ON vld.id_affectation = affectation.id_affectation;
        ');

        DB::statement("
            CREATE OR REPLACE VIEW view_ligne_resilie AS
            SELECT *
            FROM ligne
            WHERE id_statut_ligne = 4;
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_ligne_actif AS
            SELECT *
            FROM ligne
            WHERE id_statut_ligne = 3;
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_ligne_en_attente AS
            SELECT *
            FROM ligne
            WHERE id_statut_ligne = 2;
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_ligne_inactif AS
            SELECT *
            FROM ligne
            WHERE id_statut_ligne = 1;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_ligne_details CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_ligne_big_details CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_ligne_resilie CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_ligne_actif CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_ligne_en_attente CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_ligne_inactif CASCADE;');
    }
};
