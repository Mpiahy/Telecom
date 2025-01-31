<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    use HasFactory;

    protected $table = 'type_equipement';
    public $timestamps = false;
    protected $primaryKey = 'id_type_equipement';
    protected $fillable = ['type_equipement'];

    // Constantes pour les types d'équipement
    public const SMARTPHONE = 1;
    public const TELEPHONE_TOUCHE = 2;
    public const BOX = 3;

    /**
     * Scope pour récupérer uniquement les types correspondant aux téléphones.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPhones($query)
    {
        return $query->whereIn('id_type_equipement', [self::SMARTPHONE, self::TELEPHONE_TOUCHE]);
    }
    /**
     * Scope pour récupérer uniquement les types correspondant aux téléphones.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForBox($query)
    {
        return $query->whereIn('id_type_equipement', [self::BOX]);
    }
}
