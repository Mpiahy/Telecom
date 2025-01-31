<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'id_service';
    protected $fillable = ['libelle_service'];

    public function imputations()
    {
        return $this->hasMany(Imputation::class, 'id_service');
    }

    public function localisations()
    {
        return $this->hasMany(Localisation::class, 'id_service');
    }
}