<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeForfait extends Model
{
    use HasFactory;
    protected $table = 'type_forfait';
    public $timestamps = false;
    protected $primaryKey = 'id_type_forfait';
    protected $fillable = ['type_forfait'];
}
