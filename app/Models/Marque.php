<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Marque extends Model
{
    use HasFactory;
    protected $table = 'marque';
    protected $primaryKey = 'id_marque';
    public $incrementing = false;  
    protected $fillable = [
        'id_marque',
        'marque',
    ];

    /**
     * Méthode statique pour récupérer les marques Box (via la vue `view_marque_box`).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function marqueBox()
    {
        return static::query()->whereRaw('id_marque IN (SELECT id_marque FROM view_marque_box)');
    }

    /**
     * Méthode statique pour récupérer les marques Phone (via la vue `view_marque_phone`).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function marquePhone()
    {
        return static::query()->whereRaw('id_marque IN (SELECT id_marque FROM view_marque_phone)');
    }

    public static function generateId($typeEquipementId)
    {
        // Démarrer une transaction pour éviter les conditions de course
        return DB::transaction(function () use ($typeEquipementId) {
            // Vérifie si un compteur existe déjà pour cette combinaison ('marque', $typeEquipementId)
            $counter = DB::table('id_counters')
                ->where('entity', 'marque')
                ->where('type_or_marque_id', $typeEquipementId)
                ->lockForUpdate() // Verrouille la ligne pour éviter les accès concurrents
                ->first();
    
            if (!$counter) {
                // Si aucun compteur n'existe, initialiser avec le premier ID pour ce type d'équipement
                $newId = $typeEquipementId * 1000 + 1;
    
                DB::table('id_counters')->insert([
                    'entity' => 'marque',
                    'type_or_marque_id' => $typeEquipementId,
                    'last_id' => $newId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                return $newId;
            }
    
            // Incrémenter le dernier ID et mettre à jour le compteur
            $newId = $counter->last_id + 1;
    
            DB::table('id_counters')
                ->where('entity', 'marque')
                ->where('type_or_marque_id', $typeEquipementId)
                ->update([
                    'last_id' => $newId,
                    'updated_at' => now(),
                ]);
    
            return $newId;
        });
    }
        
    /**
     * Trouver ou créer une marque.
     *
     * @param  string $marqueId
     * @param  string|null $newMarque
     * @return Marque
     */
    public static function findOrCreate($marqueId, $newMarque = null, $typeEquipementId)
    {
        if ($marqueId === 'new'|| $marqueId === 'new_marque') { // Vérifier si l'utilisateur a sélectionné "Ajouter une nouvelle marque"
            if (!$newMarque) {
                throw new \InvalidArgumentException('Le champ "Nouvelle Marque" est requis.');
            }

            // Vérifiez que $typeEquipementId est défini
            if (!$typeEquipementId) {
                throw new \InvalidArgumentException('Le type d\'équipement est requis pour créer une nouvelle marque.');
            }

            // Générer un nouvel id_marque basé sur le type d'équipement
            $newId = self::generateId($typeEquipementId);

            // Créer une nouvelle marque
            return self::create([
                'id_marque' => $newId,
                'marque' => $newMarque,
            ]);
        }

        // Sinon, chercher une marque existante
        return self::findOrFail($marqueId);
    }

    // Méthode pour récupérer les marques par type d'équipement
    public static function getByType($typeId)
    {
        $startRange = $typeId * 1000; // Calcul du début de la plage
        $endRange = ($typeId + 1) * 1000; // Calcul de la fin de la plage

        // Retourner les marques filtrées
        return self::where('id_marque', '>=', $startRange)
                   ->where('id_marque', '<', $endRange)
                   ->get(['id_marque as id', 'marque as name']);
    }

}
