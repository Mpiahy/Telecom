<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EquipementService
{
    public function getEquipementData()
    {
        return DB::table('equipement')
            ->leftJoin('affectation', 'equipement.id_equipement', '=', 'affectation.id_equipement')
            ->leftJoin('utilisateur', 'affectation.id_utilisateur', '=', 'utilisateur.id_utilisateur')
            ->leftJoin('fonction', 'utilisateur.id_fonction', '=', 'fonction.id_fonction')
            ->leftJoin('localisation', 'utilisateur.id_localisation', '=', 'localisation.id_localisation')
            ->leftJoin('type_equipement', 'equipement.id_type_equipement', '=', 'type_equipement.id_type_equipement')
            ->leftJoin('statut_equipement', 'equipement.id_statut_equipement', '=', 'statut_equipement.id_statut_equipement')
            ->select(
                'statut_equipement.statut_equipement as statut',
                'type_equipement.type_equipement as type',
                DB::raw("CASE WHEN equipement.enrole = TRUE THEN 'Oui' ELSE 'Non' END as enrole"),
                'utilisateur.login',
                DB::raw("CONCAT(utilisateur.nom, ' ', utilisateur.prenom) as nom_prenom"),
                'fonction.fonction',
                'localisation.localisation',
                'affectation.debut_affectation as date_affectation',
                'affectation.fin_affectation as date_retour'
            )
            ->get()
            ->toArray();
    }
}