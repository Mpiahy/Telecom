<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'type_utilisateur'; // Nom de la table
    protected $primaryKey = 'id_type_utilisateur'; // Clé primaire
    public $timestamps = false; // Désactiver les colonnes 'created_at' et 'updated_at'

    protected $fillable = ['type_utilisateur'];
}
