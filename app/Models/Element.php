<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;

    protected $table = 'element';
    protected $primaryKey = 'id_element';
    public $timestamps = false;

    protected $fillable = [
        'libelle',
        'unite',
        'prix_unitaire_element',
    ];

    public function updatePrixElementFromRequest($validatedData, $id_element)
    {
        $element = self::find($id_element);
    
        if ($element) {
            $element->update([
                'prix_unitaire_element' => $validatedData['edt_pu']
            ]);
        } else {
            throw new \Exception("L'élément avec l'ID $id_element n'existe pas.");
        }
    }
    
}
