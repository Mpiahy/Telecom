<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FlotteService
{
    public function getSuiviFlotteData(int $annee): array
    {
        $affectations = DB::table('affectation as a')
            ->join('ligne as l', 'a.id_ligne', '=', 'l.id_ligne')
            ->join('view_forfait_prix as vfp', 'l.id_forfait', '=', 'vfp.id_forfait')
            ->join('utilisateur as u', 'a.id_utilisateur', '=', 'u.id_utilisateur')
            ->join('fonction as f', 'u.id_fonction', '=', 'f.id_fonction')
            ->join('localisation as loc', 'u.id_localisation', '=', 'loc.id_localisation')
            ->select(
                DB::raw("CASE 
                            WHEN LEFT(l.num_ligne, 4) = '+261' 
                            THEN '0' || SUBSTRING(l.num_ligne FROM 5) 
                            ELSE l.num_ligne 
                         END AS num_ligne"),
                DB::raw("CAST(l.num_sim AS TEXT) AS num_sim"),
                DB::raw("CASE 
                            WHEN a.fin_affectation IS NULL OR a.fin_affectation > make_date($annee, 12, 31)::date
                            THEN 'Attribue' 
                            ELSE 'Resilie' 
                         END AS statut_ligne"),
                'u.login',
                DB::raw("CONCAT(u.nom, ' ', u.prenom) AS nom_prenom"),
                'f.fonction',
                'loc.localisation',
                'vfp.nom_forfait',
                'vfp.prix_forfait_ht',
                'vfp.prix_jour',
                'a.debut_affectation',
                'a.fin_affectation'
            )
            ->whereYear('a.debut_affectation', '<=', $annee)
            ->where(function ($query) use ($annee) {
                $query->whereNull('a.fin_affectation')
                      ->orWhereYear('a.fin_affectation', '>=', $annee);
            })
            ->get();

        return $this->formatSuiviFlotteData($affectations, $annee);
    }

    private function formatSuiviFlotteData($affectations, int $annee): array
    {
        $rows = [];

        foreach ($affectations as $affectation) {
            $row = [
                'num_ligne'    => $affectation->num_ligne,
                'num_sim'      => $affectation->num_sim,
                'statut_ligne' => $affectation->statut_ligne,
                'login'        => $affectation->login,
                'nom_prenom'   => $affectation->nom_prenom,
                'fonction'     => $affectation->fonction,
                'localisation' => $affectation->localisation,
                'nom_forfait'  => $affectation->nom_forfait,
            ];

            $totalAnnuel = 0;
            $premierMoisDeFacturation = null;
            $remboursement = 0;

            $debutAffectation = Carbon::parse($affectation->debut_affectation);
            $finAffectation = $affectation->fin_affectation ? Carbon::parse($affectation->fin_affectation) : null;

            // 🔹 Initialisation des mois à 0
            for ($mois = 1; $mois <= 12; $mois++) {
                $row["mois_$mois"] = 0;
            }

            for ($mois = 1; $mois <= 12; $mois++) {
                $debutMois = Carbon::create($annee, $mois, 1)->startOfMonth();
                $finMois = Carbon::create($annee, $mois, 1)->endOfMonth();

                // Exclure les mois hors période d'affectation
                if ($debutAffectation->gt($finMois) || ($finAffectation && $finAffectation->lt($debutMois))) {
                    continue;
                }

                // Déterminer le premier mois de facturation
                if ($premierMoisDeFacturation === null && $debutAffectation->year == $annee && $debutAffectation->month == $mois) {
                    $premierMoisDeFacturation = $mois;
                }

                // 🔹 Premier mois d'affectation = 0 (car comptabilisation en début de mois)
                if ($premierMoisDeFacturation === $mois) {
                    continue;
                }

                $montant = $affectation->prix_forfait_ht; // Mois complet par défaut

                // 🔹 Deuxième mois d'affectation doit inclure le prorata du premier mois
                if ($premierMoisDeFacturation !== null && $mois == $premierMoisDeFacturation + 1) {
                    $joursActifsPremierMois = 30 - $debutAffectation->day + 1;
                    $montant += $joursActifsPremierMois * $affectation->prix_jour;
                }

                // 🔹 Si la résiliation a lieu en milieu de mois
                if ($finAffectation && $finAffectation->year == $annee && $finAffectation->month == $mois && $finAffectation->day < 30) {
                    $montant = $affectation->prix_forfait_ht; // Mois facturé intégralement
                    
                    // 🔹 Calcul du remboursement pour les jours non utilisés
                    $joursNonUtilises = 30 - $finAffectation->day + 1;
                    $remboursement = $joursNonUtilises * $affectation->prix_jour;

                    // 🔹 Appliquer le remboursement au mois suivant
                    $moisSuivant = $mois + 1;
                    if ($moisSuivant <= 12) {
                        if (!isset($row["mois_$moisSuivant"])) {
                            $row["mois_$moisSuivant"] = 0;
                        }
                        $row["mois_$moisSuivant"] -= round($remboursement, 2);
                    }
                }

                $row["mois_$mois"] = round($montant, 2);
                $totalAnnuel += $montant - $remboursement;
            }

            $row['total_annuel'] = round($totalAnnuel, 2);
            $rows[] = $row;
        }

        return $rows;
    }
}
