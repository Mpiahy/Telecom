<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprime toutes les données existantes dans la table users
        User::truncate();

        // Insère des utilisateurs de démonstration
        User::insert([
            [
                'login' => 'EXAMPLE',
                'email' => 'example@example.com',
                'password' => bcrypt('example'),
                'nom_usr' => 'Example',
                'prenom_usr' => 'Example',
                'isAdmin' => true,
            ],
        ]);
    }
}
