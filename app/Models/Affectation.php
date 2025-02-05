<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class Affectation extends Model
{
    use HasFactory;
    protected $table = 'affectation';
    protected $primaryKey = 'id_affectation';
    public $timestamps = true;

    protected $fillable = [
        'debut_affectation',
        'fin_affectation',
        'commentaire',
        'id_ligne',
        'id_forfait',
        'id_equipement',
        'id_utilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
    
    public function ligne()
    {
        return $this->belongsTo(Ligne::class, 'id_ligne');
    }

    public static function creerAffectation($dateDebut, $idLigne, $idForfait, $idUtilisateur)
    {
        self::create([
            'debut_affectation' => $dateDebut,
            'fin_affectation' => null,
            'id_ligne' => $idLigne,
            'id_forfait' => $idForfait,
            'id_equipement' => null,
            'id_utilisateur' => $idUtilisateur,
        ]);
    }

    public static function updateAffectation(int $idLigne, ?string $date)
    {
        if (!empty($date)) {
            return self::where('id_ligne', $idLigne)->update([
                'debut_affectation' => $date,
            ]);
        }

        return false;
    }

    public static function rslAffectation(int $idLigne, string $dateResil)
    {
        // Validation des paramètres
        if (empty($idLigne) || empty($dateResil)) {
            throw new InvalidArgumentException("Les paramètres 'idLigne' et 'dateResil' sont requis.");
        }

        // Mise à jour de l'affectation correspondant à id_ligne
        return self::where('id_ligne', $idLigne)->update([
            'fin_affectation' => $dateResil,
        ]);
    }

    public static function attrEquipement(int $idUtilisateur, int $idEquipement, string $dateDebutAffectation)
    {
        self::create([
            'debut_affectation' => $dateDebutAffectation,
            'id_utilisateur' => $idUtilisateur,
            'id_equipement' => $idEquipement,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    public static function attrLigne(int $idUtilisateur, int $idLigne, string $dateDebutAffectation)
    {
        self::create([
            'debut_affectation' => $dateDebutAffectation,
            'id_utilisateur' => $idUtilisateur,
            'id_ligne' => $idLigne,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function retourAffectationEquipement(string $retourDate, string $commentaire)
    {
        if ($retourDate <= $this->debut_affectation) {
            throw ValidationException::withMessages([
                'retour_date' => 'La date de retour doit être strictement postérieure à la date d’affectation.',
            ]);
        }

        $this->fin_affectation = $retourDate;
        $this->commentaire = $commentaire;
        $this->updated_at = now();
        $this->save();
    }

    public static function hsEquipement(int $equipementId)
    {
        $affectation = self::where('id_equipement', $equipementId)
            ->whereNull('fin_affectation')
            ->first();

        if ($affectation) {
            $affectation->fin_affectation = Carbon::now();
            $affectation->save();
        }
    }

    public static function cloturerAffectationsUtilisateur($idUtilisateur, $dateDepart, $commentaire = null)
    {
        self::where('id_utilisateur', $idUtilisateur)
            ->whereNull('fin_affectation')
            ->update([
                'fin_affectation' => $dateDepart,
                'commentaire' => $commentaire,
            ]);
    }

    public static function getTbdAnnee($annee)
    {
        Carbon::setLocale('fr');
    
        // Initialisation des mois avec des montants à 0
        $months = [];
        for ($mois = 1; $mois <= 12; $mois++) {
            $months[$mois] = [
                'mois' => Carbon::create($annee, $mois, 1)->translatedFormat('F'),
                'total_prix_forfait_ht' => 0,
            ];
        }
    
        // Récupération des affectations
        $results = DB::table('affectation as a')
            ->join('ligne as l', 'a.id_ligne', '=', 'l.id_ligne')
            ->join('view_forfait_prix as vfp', 'l.id_forfait', '=', 'vfp.id_forfait')
            ->selectRaw("
                l.id_ligne,
                a.debut_affectation,
                a.fin_affectation,
                vfp.prix_forfait_ht,
                vfp.prix_jour
            ")
            ->whereYear('a.debut_affectation', '<=', $annee)
            ->whereRaw("a.fin_affectation IS NULL OR EXTRACT(YEAR FROM a.fin_affectation) >= ?", [$annee])
            ->get();
    
        foreach ($results as $result) {
            $debut = Carbon::parse($result->debut_affectation);
            $fin = $result->fin_affectation ? Carbon::parse($result->fin_affectation) : null;
            $prixForfait = $result->prix_forfait_ht;
            $prixJour = $result->prix_jour;
    
            $premierMoisFacture = null;
    
            for ($mois = 1; $mois <= 12; $mois++) {
                $debutMois = Carbon::create($annee, $mois, 1)->startOfMonth();
                $finMois = Carbon::create($annee, $mois, 1)->endOfMonth();
    
                // Exclure les périodes hors du mois en question
                if ($debut->gt($finMois) || ($fin && $fin->lt($debutMois))) {
                    continue;
                }
    
                $montant = $prixForfait; // Par défaut, le forfait complet est facturé
    
                // Définir le premier mois de facturation
                if ($premierMoisFacture === null && $debut->year == $annee && $debut->month == $mois) {
                    $premierMoisFacture = $mois;
                    continue; // Le premier mois n'est pas facturé
                }
    
                // Prorata du mois d'entrée (au deuxième mois seulement)
                if ($premierMoisFacture !== null && $mois == $premierMoisFacture + 1) {
                    $joursActifsPremierMois = 30 - $debut->day + 1;
                    $montant += $joursActifsPremierMois * $prixJour;
                }
    
                // Gestion des résiliations en milieu de mois
                if ($fin && $fin->year == $annee && $fin->month == $mois && $fin->day < 30) {
                    $joursNonUtilises = 30 - $fin->day + 1;
                    $remboursement = $joursNonUtilises * $prixJour;
    
                    // Appliquer le remboursement au mois suivant
                    if (isset($months[$mois + 1])) {
                        $months[$mois + 1]['total_prix_forfait_ht'] -= round($remboursement, 2);
                    }
                }
    
                $months[$mois]['total_prix_forfait_ht'] += round($montant, 2);
            }
        }
    
        // 🔹 Ajout des coûts des opérations sur le mois suivant
        $operations = DB::table('view_historique_operation')
            ->whereYear('debut_operation', $annee)
            ->get();
    
        foreach ($operations as $operation) {
            $moisOperation = Carbon::parse($operation->debut_operation)->month;
            $moisFacturation = $moisOperation + 1; // Facturation le mois suivant
    
            if ($moisFacturation > 12) {
                continue; // Ne pas dépasser décembre
            }
    
            $months[$moisFacturation]['total_prix_forfait_ht'] += round($operation->prix_ht_remise_prorata, 2);
        }
    
        return $months;
    }
    
    public static function getYearlyData($annee)
    {
        Carbon::setLocale('fr');
    
        $typesLigne = DB::table('type_ligne')->pluck('type_ligne')->toArray();
        $data = [];
    
        // Initialisation des données pour chaque type de ligne
        foreach ($typesLigne as $type) {
            for ($mois = 1; $mois <= 12; $mois++) {
                $data[$type][$mois] = [
                    'mois' => Carbon::create($annee, $mois, 1)->translatedFormat('F'),
                    'total_prix_forfait_ht' => 0,
                ];
            }
        }
    
        // 🔹 1. Récupération des forfaits de base
        $forfaits = DB::table('affectation as a')
            ->join('ligne as l', 'a.id_ligne', '=', 'l.id_ligne')
            ->join('type_ligne as tl', 'l.id_type_ligne', '=', 'tl.id_type_ligne')
            ->join('view_forfait_prix as vfp', 'l.id_forfait', '=', 'vfp.id_forfait')
            ->selectRaw("
                l.id_ligne,
                a.debut_affectation,
                a.fin_affectation,
                vfp.prix_forfait_ht,
                vfp.prix_jour,
                tl.type_ligne
            ")
            ->whereYear('a.debut_affectation', '<=', $annee)
            ->whereRaw("a.fin_affectation IS NULL OR EXTRACT(YEAR FROM a.fin_affectation) >= ?", [$annee])
            ->get();
    
        foreach ($forfaits as $forfait) {
            $debut = Carbon::parse($forfait->debut_affectation);
            $fin = $forfait->fin_affectation ? Carbon::parse($forfait->fin_affectation) : null;
            $prixForfait = $forfait->prix_forfait_ht;
            $prixJour = $forfait->prix_jour;
            $type = $forfait->type_ligne;
    
            $premierMoisFacture = null;
    
            for ($mois = 1; $mois <= 12; $mois++) {
                $debutMois = Carbon::create($annee, $mois, 1)->startOfMonth();
                $finMois = Carbon::create($annee, $mois, 1)->endOfMonth();
    
                if ($debut->gt($finMois) || ($fin && $fin->lt($debutMois))) {
                    continue;
                }
    
                $montant = $prixForfait;
    
                if ($premierMoisFacture === null && $debut->year == $annee && $debut->month == $mois) {
                    $premierMoisFacture = $mois;
                    continue;
                }
    
                if ($premierMoisFacture !== null && $mois == $premierMoisFacture + 1) {
                    $joursActifsPremierMois = 30 - $debut->day + 1;
                    $montant += $joursActifsPremierMois * $prixJour;
                }
    
                if ($fin && $fin->year == $annee && $fin->month == $mois && $fin->day < 30) {
                    $joursNonUtilises = 30 - $fin->day + 1;
                    $remboursement = $joursNonUtilises * $prixJour;
    
                    if (isset($data[$type][$mois + 1])) {
                        $data[$type][$mois + 1]['total_prix_forfait_ht'] -= round($remboursement, 2);
                    }
                }
    
                $data[$type][$mois]['total_prix_forfait_ht'] += round($montant, 2);
            }
        }
    
        // 🔹 2. Ajout des coûts des opérations dans le mois suivant
        $operations = DB::table('view_historique_operation as op')
            ->join('ligne as l', 'op.id_ligne', '=', 'l.id_ligne')
            ->join('type_ligne as tl', 'l.id_type_ligne', '=', 'tl.id_type_ligne')
            ->selectRaw("
                op.debut_operation,
                op.prix_ht_remise_prorata,
                tl.type_ligne
            ")
            ->whereYear('op.debut_operation', $annee)
            ->get();
    
        foreach ($operations as $operation) {
            $moisOperation = Carbon::parse($operation->debut_operation)->month;
            $moisFacturation = $moisOperation + 1;
    
            if ($moisFacturation > 12) {
                continue;
            }
    
            $data[$operation->type_ligne][$moisFacturation]['total_prix_forfait_ht'] += round($operation->prix_ht_remise_prorata, 2);
        }
    
        // 🔹 3. Correction du total annuel (somme des 12 mois)
        foreach ($data as $type => $moisData) {
            $totalAnnuel = 0;
            for ($mois = 1; $mois <= 12; $mois++) {
                $totalAnnuel += $moisData[$mois]['total_prix_forfait_ht'];
            }
            $data[$type]['total_annuel'] = round($totalAnnuel, 2);
        }
    
        // 🔹 4. Ajout du "Total Général" (somme de tous les types)
        $data['Total Général'] = [];
        for ($mois = 1; $mois <= 12; $mois++) {
            $moisTotal = 0;
            foreach ($data as $type => $moisData) {
                if ($type !== 'Total Général') {
                    $moisTotal += $moisData[$mois]['total_prix_forfait_ht'];
                }
            }
            $data['Total Général'][$mois] = [
                'mois' => Carbon::create($annee, $mois, 1)->translatedFormat('F'),
                'total_prix_forfait_ht' => round($moisTotal, 2),
            ];
        }
    
        // 🔹 5. Calcul du total général annuel
        $grandTotalAnnuel = 0;
        for ($mois = 1; $mois <= 12; $mois++) {
            $grandTotalAnnuel += $data['Total Général'][$mois]['total_prix_forfait_ht'];
        }
        $data['Total Général']['total_annuel'] = round($grandTotalAnnuel, 2);
    
        return $data;
    }
      
}
