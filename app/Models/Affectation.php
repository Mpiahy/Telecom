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
    
        // Récupération des affectations
        $results = DB::table('affectation as a')
            ->join('ligne as l', 'a.id_ligne', '=', 'l.id_ligne')
            ->join('view_forfait_prix as vfp', 'l.id_forfait', '=', 'vfp.id_forfait')
            ->selectRaw("
                a.debut_affectation,
                a.fin_affectation,
                vfp.prix_forfait_ht,
                vfp.prix_jour
            ")
            ->whereYear('a.debut_affectation', '<=', $annee)
            ->whereRaw("a.fin_affectation IS NULL OR EXTRACT(YEAR FROM a.fin_affectation) >= ?", [$annee])
            ->get();
    
        // Initialisation des mois avec des montants à 0
        $months = [];
        for ($mois = 1; $mois <= 12; $mois++) {
            $months[$mois] = [
                'mois' => Carbon::create($annee, $mois, 1)->translatedFormat('F'),
                'total_prix_forfait_ht' => 0,
            ];
        }
    
        // Parcours des affectations
        foreach ($results as $result) {
            $debut = Carbon::parse($result->debut_affectation);
            $fin = $result->fin_affectation ? Carbon::parse($result->fin_affectation) : null;
            $prixForfait = $result->prix_forfait_ht;
            $prixJour = $result->prix_jour;
    
            $premierMoisFacture = null;
    
            for ($mois = 1; $mois <= 12; $mois++) {
                $debutMois = Carbon::create($annee, $mois, 1)->startOfMonth();
                $finMois = Carbon::create($annee, $mois, 1)->endOfMonth();
    
                // Exclure les mois hors période d'affectation
                if ($debut->gt($finMois) || ($fin && $fin->lt($debutMois))) {
                    continue;
                }
    
                // Définir le premier mois de facturation
                if ($premierMoisFacture === null && $debut->year == $annee && $debut->month == $mois) {
                    $premierMoisFacture = $mois;
                    continue; // Le premier mois n'est pas facturé
                }
    
                $montant = $prixForfait; // Par défaut, le mois est facturé en entier
    
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
            $data[$type]['total_annuel'] = 0;
        }
    
        $results = DB::table('affectation as a')
            ->join('ligne as l', 'a.id_ligne', '=', 'l.id_ligne')
            ->join('type_ligne as tl', 'l.id_type_ligne', '=', 'tl.id_type_ligne')
            ->join('view_forfait_prix as vfp', 'l.id_forfait', '=', 'vfp.id_forfait')
            ->selectRaw("
                a.debut_affectation,
                a.fin_affectation,
                vfp.prix_forfait_ht,
                vfp.prix_jour,
                tl.type_ligne
            ")
            ->whereYear('a.debut_affectation', '<=', $annee)
            ->whereRaw("a.fin_affectation IS NULL OR EXTRACT(YEAR FROM a.fin_affectation) >= ?", [$annee])
            ->get();
    
        foreach ($results as $result) {
            $debut = Carbon::parse($result->debut_affectation);
            $fin = $result->fin_affectation ? Carbon::parse($result->fin_affectation) : null;
            $prixForfait = $result->prix_forfait_ht;
            $prixJour = $result->prix_jour;
            $type = $result->type_ligne;
    
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
                    if (isset($data[$type][$mois + 1])) {
                        $data[$type][$mois + 1]['total_prix_forfait_ht'] -= round($remboursement, 2);
                    }
                }
    
                $data[$type][$mois]['total_prix_forfait_ht'] += round($montant, 2);
                $data[$type]['total_annuel'] += round($montant, 2);
            }
        }
    
        // Calcul des totaux globaux
        $totauxParMois = [];
        for ($mois = 1; $mois <= 12; $mois++) {
            $totauxParMois[$mois] = [
                'mois' => Carbon::create($annee, $mois, 1)->translatedFormat('F'),
                'total_prix_forfait_ht' => 0, // Initialisation à 0
            ];
        }
        $totauxParMois['total_annuel'] = 0;

        // Recalcul des totaux pour chaque mois et chaque type
        foreach ($data as $type => $moisData) {
            // Ignorer les totaux globaux existants (éviter les erreurs de double comptage)
            if ($type === 'Total') {
                continue;
            }

            // Additionner les montants pour chaque mois
            for ($mois = 1; $mois <= 12; $mois++) {
                $totauxParMois[$mois]['total_prix_forfait_ht'] += $moisData[$mois]['total_prix_forfait_ht'];
            }
        }

        // Calcul du total annuel global basé sur les nouveaux totaux mensuels
        for ($mois = 1; $mois <= 12; $mois++) {
            $totauxParMois['total_annuel'] += $totauxParMois[$mois]['total_prix_forfait_ht'];
        }

        // Ajout des totaux recalculés dans les données
        $data['Total'] = $totauxParMois;
    
        return $data;
    }
    

}
