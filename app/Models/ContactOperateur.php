<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactOperateur extends Model
{
    use HasFactory;
    protected $table = 'contact_operateur';
    protected $primaryKey = 'id_contact';
    public $timestamps = true;
    protected $fillable = [
        'nom',
        'email',
        'id_operateur',
    ];
    public function operateur()
    {
        return $this->belongsTo(Operateur::class, 'id_operateur', 'id_operateur');
    }

    public function updateContact(array $data)
    {
        $this->update([
            'nom' => $data['nom_contact'],
            'email' => $data['email_contact'],
        ]);
    }
}