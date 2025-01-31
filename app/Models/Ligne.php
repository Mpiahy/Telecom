<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Ligne extends Model
{
    use HasFactory;

    protected $table = 'ligne';
    protected $primaryKey = 'id_ligne';
    public $timestamps = true;

    protected $fillable = [
        'num_ligne',
        'num_sim',
        'id_forfait',
        'id_statut_ligne',
        'id_type_ligne',
        'id_operateur',
    ];

    public function forfait()
    {
        return $this->belongsTo(Forfait::class, 'id_forfait');
    }

    public function operateur()
    {
        return $this->belongsTo(Operateur::class, 'id_operateur');
    }
    
    // Mise à jour du statut de la ligne
    public function updateStatut($statut)
    {
        $this->update(['id_statut_ligne' => $statut]);
    }

    public static function getLignesWithDetails($filters = [], $perPage)
    {
        $query = DB::table('view_ligne_big_details');

        // Filtre par statut
        if (!empty($filters['statut'])) {
            $query->where('id_statut_ligne', $filters['statut']);
        }
        // Filtre par type
        if (!empty($filters['type'])) {
            $query->where('id_type_ligne', $filters['type']);
        }

        // Recherche par numéro de ligne
        if (!empty($filters['search_ligne_num'])) {
            $query->where('num_ligne', 'like', '%' . $filters['search_ligne_num'] . '%');
        }

        // Recherche par numéro de SIM
        if (!empty($filters['search_ligne_sim'])) {
            $query->where('num_sim', 'like', '%' . $filters['search_ligne_sim'] . '%');
        }

        // Recherche par utilisateur
        if (!empty($filters['search_ligne_user'])) {
            $query->where('login', 'ilike', '%' . $filters['search_ligne_user'] . '%');
        }

        // Retourner une pagination au lieu de `get()`
        return $query->paginate($perPage);
    }


    public static function getLignesWithBigDetails($id_ligne)
    {
        $sql = "SELECT * FROM view_ligne_big_details";
        
        // Ajout de la clause WHERE si $id_ligne est fourni
        if (!empty($id_ligne)) {
            $sql .= " WHERE id_ligne = :id_ligne";
            return DB::select($sql, ['id_ligne' => $id_ligne]);
        }

        return DB::select($sql);
    }
    
    public static function createLigneWithDetails($data)
    {
        $ligne = self::create([
            'num_ligne' => null,
            'num_sim' => $data['act_sim'],
            'id_forfait' => $data['act_forfait'],
            'id_statut_ligne' => StatutLigne::STATUT_EN_ATTENTE,
            'id_type_ligne' => $data['act_type'],
            'id_operateur' => $data['act_operateur'],
        ]);
        return $ligne;
    }

    public static function reactLigne($idLigne, $data)
    {
        return self::where('id_ligne', $idLigne)->update([
            'num_ligne' => null, // Réinitialise le numéro de ligne
            'id_forfait' => $data['react_forfait'], // Met à jour le forfait
            'id_statut_ligne' => StatutLigne::STATUT_EN_ATTENTE, // Statut en attente
            'id_type_ligne' => $data['react_type'], // Met à jour le type de ligne
        ]);
    }

    public function enrLigne($numLigne)
    {
        $this->update([
            'num_ligne' => $numLigne,
            'id_statut_ligne' => StatutLigne::STATUT_ATTRIBUE,
        ]);
    }

    public static function updateLigne(int $idLigne, array $data)
    {
        $updateData = [
            'num_sim' => $data['edt_sim'],
            'id_operateur' => $data['edt_operateur'],
            'id_type_ligne' => $data['edt_type'],
            'id_forfait' => $data['edt_forfait'],
        ];

        // Inclure `num_ligne` uniquement s'il est présent
        if (!empty($data['edt_ligne'])) {
            $updateData['num_ligne'] = $data['edt_ligne'];
        }

        return self::where('id_ligne', $idLigne)->update($updateData);
    }

    public function rslLigne($idLigne)
    {
        // Vérifiez si l'ID est valide
        if (!$idLigne) {
            throw new InvalidArgumentException("L'identifiant de la ligne est requis.");
        }

        // Mettre à jour la ligne avec les conditions spécifiées
        $this->where('id_ligne', $idLigne)->update([
            'id_statut_ligne' => StatutLigne::STATUT_RESILIE,
        ]);
    }

    public static function attrLigne($idLigne)
    {
        // Vérifier si l'ID est valide
        if (!$idLigne) {
            throw new InvalidArgumentException("L'identifiant de la ligne est requis.");
        }

        // Mettre à jour la ligne avec les conditions spécifiées
        self::where('id_ligne', $idLigne)->update([
            'id_statut_ligne' => StatutLigne::STATUT_ATTRIBUE,
        ]);
    }


    // Compter les lignes Résiliées
    public static function countResilie()
    {
        return DB::select("SELECT COUNT(*) AS total FROM view_ligne_resilie")[0]->total;
    }

    // Compter les lignes Actives
    public static function countActif()
    {
        return DB::select("SELECT COUNT(*) AS total FROM view_ligne_actif")[0]->total;
    }

    // Compter les lignes En attente
    public static function countEnAttente()
    {
        return DB::select("SELECT COUNT(*) AS total FROM view_ligne_en_attente")[0]->total;
    }

    // Compter les lignes Inactives
    public static function countInactif()
    {
        return DB::select("SELECT COUNT(*) AS total FROM view_ligne_inactif")[0]->total;
    }

    public static function getStats()
    {
        return [
            'ligneResilie' => self::countResilie(),
            'ligneActif' => self::countActif(),
            'ligneEnAttente' => self::countEnAttente(),
            'ligneInactif' => self::countInactif(),
        ];
    }

    public static function getHistoriqueLigne($id_ligne)
    {
        // Vérifie si l'équipement existe
        $equipmentExists = DB::table('ligne')
            ->where('id_ligne', $id_ligne)
            ->exists();

        if (!$equipmentExists) {
            return null; // Retourne null si l'équipement n'existe pas
        }

        // Récupère les détails de l'historique s'il existe
        $sql = "SELECT * FROM view_historique_ligne WHERE id_ligne = :id_ligne";
        return DB::select($sql, ['id_ligne' => $id_ligne]);
    }

    public function resilier()
    {
        $this->id_statut_ligne = StatutLigne::STATUT_RESILIE;
        $this->save();
    }

    public static function resilierLignes(array $lignes)
    {
        foreach ($lignes as $idLigne) {
            $ligne = self::find($idLigne);
            if ($ligne) {
                $ligne->resilier();
            }
        }
    }

}
