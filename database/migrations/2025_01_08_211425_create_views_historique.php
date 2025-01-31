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
        // View pour avoir historique equipement
        DB::statement('
            CREATE OR REPLACE VIEW view_historique_equipement AS
            SELECT 
                aff.id_equipement,
                u.nom AS nom,
                u.prenom AS prenom,
                u.login AS login,
                loc.localisation AS localisation,
                aff.commentaire,
                aff.debut_affectation,
                aff.fin_affectation
            FROM 
                (SELECT * FROM affectation WHERE id_equipement IS NOT NULL) aff
            INNER JOIN utilisateur u ON aff.id_utilisateur = u.id_utilisateur
            LEFT JOIN localisation loc ON u.id_localisation = loc.id_localisation;
        ');
        // View pour avoir historique equipement utilisateur
        DB::statement('
            CREATE OR REPLACE VIEW view_historique_user_equipement AS
            SELECT 
                aff.id_utilisateur,
                eq.id_equipement,
                ma.marque,
                mo.nom_modele AS modele,
                te.type_equipement,
                eq.imei,
                eq.serial_number,
                aff.commentaire,
                aff.debut_affectation,
                aff.fin_affectation
            FROM 
                affectation aff
            INNER JOIN equipement eq ON aff.id_equipement = eq.id_equipement
            INNER JOIN type_equipement te ON eq.id_type_equipement = te.id_type_equipement
            INNER JOIN modele mo ON eq.id_modele = mo.id_modele
            INNER JOIN marque ma ON mo.id_marque = ma.id_marque
            WHERE 
                aff.id_equipement IS NOT NULL;
        ');
        // View pour avoir historique ligne utilisateur
        DB::statement('
            CREATE OR REPLACE VIEW view_historique_user_ligne AS
            SELECT 
                aff.id_utilisateur,
                li.id_ligne,
                li.num_ligne,
                li.num_sim,
                f.nom_forfait,
                tl.type_ligne,
                aff.commentaire,
                aff.debut_affectation,
                aff.fin_affectation,
                co.email
            FROM 
                affectation aff
            INNER JOIN ligne li ON aff.id_ligne = li.id_ligne
            INNER JOIN forfait f ON li.id_forfait = f.id_forfait
            INNER JOIN type_ligne tl ON li.id_type_ligne = tl.id_type_ligne
            INNER JOIN operateur o ON f.id_operateur = o.id_operateur
            LEFT JOIN contact_operateur co ON o.id_operateur = co.id_operateur
            WHERE 
                aff.id_ligne IS NOT NULL;
        ');
        // View pour avoir historique ligne utilisateur
        DB::statement('
            CREATE OR REPLACE VIEW view_historique_ligne AS
            SELECT 
                l.id_ligne,
                u.nom,
                u.prenom,
                u.login,
                loc.localisation,
                l.num_ligne,
                l.num_sim,
                tf.type_forfait,
                f.nom_forfait AS forfait,
                vfp.prix_forfait_ht,
                a.debut_affectation,
                a.fin_affectation
            FROM 
                affectation a
            INNER JOIN 
                utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN 
                localisation loc ON u.id_localisation = loc.id_localisation
            INNER JOIN 
                ligne l ON a.id_ligne = l.id_ligne
            INNER JOIN 
                forfait f ON a.id_forfait = f.id_forfait
            INNER JOIN 
                type_forfait tf ON f.id_type_forfait = tf.id_type_forfait
            INNER JOIN 
                view_forfait_prix vfp ON f.id_forfait = vfp.id_forfait
            WHERE 
                a.id_ligne IS NOT NULL;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les vues dans l'ordre inverse pour éviter les dépendances
        DB::statement('DROP VIEW IF EXISTS view_historique_equipement CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_historique_user_equipement CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_historique_user_ligne CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_historique_ligne CASCADE;');
    }
};
