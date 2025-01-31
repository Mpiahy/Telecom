<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutEquipement extends Model
{
    use HasFactory;
    protected $table = 'statut_equipement';
    public $timestamps = false;
    protected $primaryKey = 'id_statut_equipement';
    protected $fillable = ['statut_equipement'];
    const STATUT_NOUVEAU = 1;
    const STATUT_ATTRIBUE = 2;
    const STATUT_RETOURNE = 3;
    const STATUT_HS = 4;
    public static function getBootstrapClass($id_statut_equipement)
    {
        switch ($id_statut_equipement) {
            case 'Retourné':
                return 'warning';
            case 'Nouveau':
                return 'info';
            case 'Attribué':
                return 'success';
            case 'HS':
                return 'danger';
        }
    }

    public static function markAsHS($equipement)
    {
        $equipement->update([
            'id_statut_equipement' => self::STATUT_HS,
        ]);
    }

    public static function markAsRetourne($equipement)
    {
        $equipement->update([
            'id_statut_equipement' => self::STATUT_RETOURNE,
        ]);
    }
}