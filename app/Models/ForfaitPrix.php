<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForfaitPrix extends Model
{
    use HasFactory;

    protected $table = 'view_forfait_prix';
    public $timestamps = false;
    protected $primaryKey = null; // Pas de clé primaire
    public $incrementing = false; // Pas d'auto-incrément    
    protected $fillable = [
        'id_forfait',
        'nom_forfait',
        'prix_forfait_ht_non_remise',
        'droit_d_accise',
        'remise_pied_de_page',
        'prix_forfait_ht',
    ];

    // Accessors pour formater les montants
    public function getPrixForfaitHtNonRemiseAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' Ar';
    }

    public function getDroitDAcciseAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' Ar';
    }

    public function getRemisePiedDePageAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' Ar';
    }

    public function getPrixForfaitHtAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' Ar';
    }
}
