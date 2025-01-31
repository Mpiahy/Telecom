<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;
    protected $table = 'fonction';
    protected $primaryKey = 'id_fonction';
    public $timestamps = false;
    protected $fillable = ['fonction'];

    /**
     * Crée une nouvelle fonction et retourne l'instance créée.
     *
     * @param string $fonctionNom Le nom de la fonction à créer
     * @return self
     */
    public static function creerNouvelleFonction(string $fonctionNom): self
    {
        return self::create(['fonction' => $fonctionNom]);
    }
}
