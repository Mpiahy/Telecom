<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Modele extends Model
{
    use HasFactory;
    protected $table = 'modele';
    protected $primaryKey = 'id_modele';
    public $incrementing = false; 
    protected $fillable = [
        'id_modele',
        'nom_modele',
        'id_marque',
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class, 'id_marque');
    }

    /**
     * Méthode statique pour récupérer les modèles Box (via la vue `view_modele_box`).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function modeleBox()
    {
        return static::query()->whereRaw('id_modele IN (SELECT id_modele FROM view_modele_box)');
    }

    /**
     * Méthode statique pour récupérer les modèles Phone (via la vue `view_modele_phone`).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function modelePhone()
    {
        return static::query()->whereRaw('id_modele IN (SELECT id_modele FROM view_modele_phone)');
    }

    public static function generateId($marqueId)
    {
        return DB::transaction(function () use ($marqueId) {
            $counter = DB::table('id_counters')
                ->where('entity', 'modele')
                ->where('type_or_marque_id', $marqueId)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                $newId = $marqueId * 1000 + 1;

                DB::table('id_counters')->insert([
                    'entity' => 'modele',
                    'type_or_marque_id' => $marqueId,
                    'last_id' => $newId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $newId;
            }

            $newId = $counter->last_id + 1;

            DB::table('id_counters')
                ->where('entity', 'modele')
                ->where('type_or_marque_id', $marqueId)
                ->update([
                    'last_id' => $newId,
                    'updated_at' => now(),
                ]);

            return $newId;
        });
    }     
      
    /**
     * Trouver ou créer un modèle.
     *
     * @param  string $modeleId
     * @param  string|null $newModele
     * @param  int $marqueId
     * @return Modele
     */
    public static function findOrCreate($modeleId, $newModele = null, $marqueId)
    {
        if ($modeleId === 'new') {
            // Génère un nouvel id_modele basé sur l'id_marque
            $newId = self::generateId($marqueId);
    
            return self::create([
                'id_modele' => $newId,
                'nom_modele' => $newModele,
                'id_marque' => $marqueId,
            ]);
        }
    
        return self::findOrFail($modeleId);
    }          

    // Méthode pour récupérer les modèles par marque
    public static function getByMarque($marqueId)
    {
        $startRange = $marqueId * 1000; // Calcul du début de la plage
        $endRange = ($marqueId + 1) * 1000; // Calcul de la fin de la plage

        // Retourner les modèles filtrés
        return self::where('id_modele', '>=', $startRange)
                   ->where('id_modele', '<', $endRange)
                   ->get(['id_modele as id', 'nom_modele as name']);
    }
}