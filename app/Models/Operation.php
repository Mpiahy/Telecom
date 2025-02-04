<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operation';
    protected $primaryKey = 'id_operation';
    public $timestamps = true;

    protected $fillable = [
        'id_ligne',
        'id_element',
        'quantite',
        'debut_operation',
        'commentaire',
    ];

    /**
     * Créer une nouvelle opération avec la quantité existante de l'élément.
     *
     * @param int $id_ligne
     * @param int $id_element
     * @param string $debut_operation
     * @param string|null $commentaire
     * @return bool
     */
    public static function ajouterOperation($id_ligne, $id_element, $debut_operation, $commentaire = null)
    {
        try {
            // Récupérer la quantité de l'élément pour le forfait de la ligne
            $element = DB::table('view_element_prix')
                ->where('id_element', $id_element)
                ->where('id_forfait', function ($query) use ($id_ligne) {
                    $query->select('id_forfait')
                        ->from('view_ligne_big_details')
                        ->where('id_ligne', $id_ligne)
                        ->limit(1);
                })
                ->select('quantite')
                ->first();

            if (!$element) {
                Log::error("Élément non valide pour id_ligne: $id_ligne, id_element: $id_element");
                return false;
            }

            // Créer l'opération avec Eloquent
            self::create([
                'id_ligne' => $id_ligne,
                'id_element' => $id_element,
                'quantite' => $element->quantite,
                'debut_operation' => $debut_operation,
                'commentaire' => $commentaire,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'opération : ' . $e->getMessage());
            return false;
        }
    }
}
