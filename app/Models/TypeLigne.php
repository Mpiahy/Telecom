<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeLigne extends Model
{
    use HasFactory;
    protected $table = 'type_ligne';
    public $timestamps = false;
    protected $primaryKey = 'id_type_ligne';
    protected $fillable = ['type_ligne'];
    const TYPE_STANDARD = 1;
    const TYPE_INTERNET = 2;
    const TYPE_FIXE = 3;

    public static function getLignesTypes()
    {
        return self::whereIn('id_type_ligne', [self::TYPE_STANDARD, self::TYPE_INTERNET])->get();
    }
}