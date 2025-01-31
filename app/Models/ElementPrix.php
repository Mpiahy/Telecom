<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementPrix extends Model
{
    use HasFactory;

    protected $table = 'view_element_prix';
    public $timestamps = false;
    protected $primaryKey = null; // Pas de clé primaire
    public $incrementing = false; // Pas d'auto-incrément
    protected $fillable = [
        'id_forfait',
        'id_element',
        'libelle',
        'quantite',
        'unite',
        'prix_unitaire_element',
        'prix_total_element',
    ];

    // Accessors pour formater les montants
    public function getPrixUnitaireElementAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' Ar';
    }

    public function getPrixTotalElementAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' Ar';
    }
}
