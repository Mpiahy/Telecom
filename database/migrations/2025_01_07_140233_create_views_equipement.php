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
        // VUE EQUIPEMENT = PHONE
        DB::statement('
            CREATE OR REPLACE VIEW view_equipement_phones AS 
            SELECT * FROM equipement WHERE id_type_equipement IN (1, 2);
        ');

        // VUE EQUIPEMENT = BOX
        DB::statement('
            CREATE OR REPLACE VIEW view_equipement_box AS 
            SELECT * FROM equipement WHERE id_type_equipement = 3;
        ');

        // View pour les marques Phone
        DB::statement('
            CREATE OR REPLACE VIEW view_marque_phone AS
            SELECT * 
            FROM marque
            WHERE id_marque >= 1000 AND id_marque < 3000;
        ');

        // View pour les marques Box
        DB::statement('
            CREATE OR REPLACE VIEW view_marque_box AS
            SELECT * 
            FROM marque
            WHERE id_marque >= 3000 AND id_marque < 4000;
        ');

        // View pour les modèles Phone
        DB::statement('
            CREATE OR REPLACE VIEW view_modele_phone AS
            SELECT * 
            FROM modele
            WHERE id_modele >= 1000000 AND id_modele < 3000000;
        ');

        // View pour les modèles Box
        DB::statement('
            CREATE OR REPLACE VIEW view_modele_box AS
            SELECT * 
            FROM modele
            WHERE id_modele >= 3000000 AND id_modele < 4000000;
        ');

        // View pour les équipements actifs
        DB::statement('
            CREATE OR REPLACE VIEW view_equipement_actif AS
            SELECT *
            FROM equipement
            WHERE id_statut_equipement = 2;
        ');

        // View pour les équipements inactifs
        DB::statement('
            CREATE OR REPLACE VIEW view_equipement_inactif AS
            SELECT *
            FROM equipement
            WHERE id_statut_equipement IN (1, 3);
        ');

        // View pour les équipements hors service (HS)
        DB::statement('
            CREATE OR REPLACE VIEW view_equipement_hs AS
            SELECT *
            FROM equipement
            WHERE id_statut_equipement = 4;
        ');

        // View pour avoir Phones inactifs
        DB::statement('
            CREATE OR REPLACE VIEW view_phones_inactif AS
            SELECT 
                e.id_equipement, 
                e.imei, 
                e.serial_number, 
                ma.marque, 
                mo.nom_modele AS modele
            FROM 
                view_equipement_inactif e
            JOIN 
                modele mo ON e.id_modele = mo.id_modele
            JOIN 
                marque ma ON mo.id_marque = ma.id_marque
            WHERE 
                e.id_type_equipement IN (1, 2);
        ');

        // View pour avoir Box inactifs
        DB::statement('
            CREATE OR REPLACE VIEW view_box_inactif AS
            SELECT 
                e.id_equipement, 
                e.imei, 
                e.serial_number, 
                ma.marque, 
                mo.nom_modele AS modele
            FROM 
                view_equipement_inactif e
            JOIN 
                modele mo ON e.id_modele = mo.id_modele
            JOIN 
                marque ma ON mo.id_marque = ma.id_marque
            WHERE 
                e.id_type_equipement = 3;
        ');

        // View pour avoir Phones avec détails
        DB::statement('
            CREATE OR REPLACE VIEW view_phones_details AS
            SELECT 
                e.id_equipement,
                m.marque,
                mo.nom_modele AS modele,
                e.imei,
                e.serial_number,
                te.type_equipement,
                se.statut_equipement,
                e.enrole,
                u.nom,
                u.prenom,
                u.login,
                l.localisation,
                a.id_affectation,
                a.debut_affectation,
                a.fin_affectation
            FROM view_equipement_phones e
            LEFT JOIN modele mo ON e.id_modele = mo.id_modele
            LEFT JOIN marque m ON mo.id_marque = m.id_marque
            LEFT JOIN type_equipement te ON e.id_type_equipement = te.id_type_equipement
            LEFT JOIN statut_equipement se ON e.id_statut_equipement = se.id_statut_equipement
            LEFT JOIN (
                SELECT DISTINCT ON (id_equipement) *
                FROM affectation
                ORDER BY id_equipement, debut_affectation DESC
            ) a ON e.id_equipement = a.id_equipement
            LEFT JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            LEFT JOIN localisation l ON u.id_localisation = l.id_localisation;
        ');

        // View pour avoir Box avec détails
        DB::statement('
            CREATE OR REPLACE VIEW view_box_details AS
            SELECT 
                e.id_equipement,
                m.marque,
                mo.nom_modele AS modele,
                e.imei,
                e.serial_number,
                te.type_equipement,
                se.statut_equipement,
                u.nom,
                u.prenom,
                u.login,
                l.localisation,
                a.id_affectation,
                a.debut_affectation,
                a.fin_affectation
            FROM view_equipement_box e
            LEFT JOIN modele mo ON e.id_modele = mo.id_modele
            LEFT JOIN marque m ON mo.id_marque = m.id_marque
            LEFT JOIN type_equipement te ON e.id_type_equipement = te.id_type_equipement
            LEFT JOIN statut_equipement se ON e.id_statut_equipement = se.id_statut_equipement
            LEFT JOIN (
                SELECT DISTINCT ON (id_equipement) *
                FROM affectation
                ORDER BY id_equipement, debut_affectation DESC
            ) a ON e.id_equipement = a.id_equipement
            LEFT JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            LEFT JOIN localisation l ON u.id_localisation = l.id_localisation;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les vues dans l'ordre inverse pour éviter les dépendances
        DB::statement('DROP VIEW IF EXISTS view_box_details CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_phones_details CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_box_inactif CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_phones_inactif CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_equipement_hs CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_equipement_inactif CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_equipement_actif CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_modele_box CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_modele_phone CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_marque_box CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_marque_phone CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_equipement_box CASCADE;');
        DB::statement('DROP VIEW IF EXISTS view_equipement_phones CASCADE;');
    }
};
