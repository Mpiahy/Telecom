<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ForfaitPrix;

class Forfait extends Model
{
    use HasFactory;

    protected $table = 'forfait';
    protected $primaryKey = 'id_forfait';
    public $timestamps = false;

    protected $fillable = [
        'nom_forfait',
        'id_type_forfait',
        'id_operateur',
    ];

    /**
     * Récupère les éléments associés au forfait via la vue 'elementPrix'.
     */
    public function elements()
    {
        return $this->hasMany(ElementPrix::class, 'id_forfait', 'id_forfait');
    }

    /**
     * Récupère les détails complets d'un forfait (données depuis 'forfaitPrix').
     *
     * @return ForfaitPrix|null
     */
    public function getDetails()
    {
        return ForfaitPrix::where('id_forfait', $this->id_forfait)->first();
    }

    /**
     * Méthode statique pour simplifier la récupération des éléments et des détails.
     *
     * @param int $id
     * @return array|null
     */
    public static function getForfaitWithDetails($id)
    {
        $forfait = self::find($id);

        if (!$forfait) {
            return null; // Si le forfait n'existe pas
        }

        return [
            'details' => $forfait->getDetails(),
            'elements' => $forfait->elements()->orderBy('id_element', 'asc')->get(),
        ];
    }

    /**
     * Récupère les forfaits avec des filtres optionnels.
     * Si aucun type de forfait n'est fourni, sélectionne le premier type de forfait par défaut.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFilteredForfaits(array $filters = [])
    {
        // Initialiser la requête
        $query = self::query();

        // Si aucun type de forfait n'est fourni, utiliser le premier type de forfait
        if (empty($filters['filter_type_forfait'])) {
            $firstTypeForfait = TypeForfait::first(); // Récupérer le premier type de forfait
            if ($firstTypeForfait) {
                $filters['filter_type_forfait'] = $firstTypeForfait->id_type_forfait;
            }
        }

        // Appliquer les filtres si disponibles
        if (!empty($filters['filter_type_forfait'])) {
            $query->where('id_type_forfait', $filters['filter_type_forfait']);
        }

        if (!empty($filters['filter_operateur'])) {
            $query->where('id_operateur', $filters['filter_operateur']);
        }

        // Exécuter et retourner les résultats
        return $query->orderBy('id_forfait', 'asc')->get();
    }

}
