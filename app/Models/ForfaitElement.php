<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ForfaitElement extends Model
{
    use HasFactory;

    protected $table = 'forfait_element';
    public $timestamps = false;
    protected $primaryKey = null; // Clé composite
    public $incrementing = false;

    protected $fillable = [
        'id_element',
        'id_forfait',
        'quantite',
    ];

    public function element()
    {
        return $this->belongsTo(Element::class, 'id_element', 'id_element');
    }

    public function forfait()
    {
        return $this->belongsTo(Forfait::class, 'id_forfait', 'id_forfait');
    }

    public function updateQuantiteFromRequest($validatedData, $id_element, $id_forfait)
    {
        // Exécuter une requête SQL brute pour mettre à jour la quantité
        $affectedRows = DB::update(
            'UPDATE forfait_element SET quantite = ? WHERE id_element = ? AND id_forfait = ?',
            [$validatedData['edt_qu'], $id_element, $id_forfait]
        );
    
        // Vérifier si la mise à jour a réussi
        if ($affectedRows === 0) {
            // Si aucune ligne n'a été affectée, lever une exception ou gérer l'erreur
            throw new \Exception("Enregistrement introuvable ou non mis à jour pour id_element={$id_element} et id_forfait={$id_forfait}");
        }
    }
    public function deleteQuantiteFromRequest($validatedData, $id_element, $id_forfait)
    {
        // Exécuter une requête SQL brute pour mettre à jour la quantité
        $affectedRows = DB::update(
            'UPDATE forfait_element SET quantite = ? WHERE id_element = ? AND id_forfait = ?',
            [$validatedData['del_qu'], $id_element, $id_forfait]
        );
    
        // Vérifier si la mise à jour a réussi
        if ($affectedRows === 0) {
            // Si aucune ligne n'a été affectée, lever une exception ou gérer l'erreur
            throw new \Exception("Enregistrement introuvable ou non mis à jour pour id_element={$id_element} et id_forfait={$id_forfait}");
        }
    }
    
}
