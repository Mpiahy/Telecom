<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    use HasFactory;

    // Spécifie le nom de la table associée
    protected $table = 'operateur';
    protected $primaryKey = 'id_operateur';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'id_operateur', 
        'nom_operateur',
    ];
}
