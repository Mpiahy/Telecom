<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['login', 'email', 'password', 'nom_usr', 'prenom_usr', 'isAdmin']; // Ajout de isAdmin

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'isAdmin' => 'boolean', // Cast automatique de la colonne isAdmin en booléen
    ];

    /**
     * Met à jour les détails de l'utilisateur.
     *
     * @param array $data
     * @return bool|string Retourne true si succès, ou un message d'erreur
     */
    public function updateUserDetails(array $data)
    {
        try {
            // Mise à jour des champs
            $this->update([
                'login' => $data['login_usr'],
                'email' => $data['email_usr'],
                'nom_usr' => $data['nom_usr'],
                'prenom_usr' => $data['prenom_usr'],
            ]);

            return true; // Mise à jour réussie
        } catch (Exception $e) {
            return $e->getMessage(); // Retourner le message d'erreur
        }
    }

    public function updatePassword(string $newPassword)
    {
        try {
            // Mettre à jour le mot de passe en le hachant
            $this->update([
                'password' => Hash::make($newPassword),
            ]);

            return true;
        } catch (Exception $e) {
            return $e->getMessage(); // Retourner le message d'erreur en cas de problème
        }
    }

    public function toggleAdmin()
    {
        $this->isAdmin = !$this->isAdmin;
        return $this->save();
    }

}
