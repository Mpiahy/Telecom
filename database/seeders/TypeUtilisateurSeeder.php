<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeUtilisateur;

class TypeUtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run(): void
    {
        TypeUtilisateur::truncate();

        TypeUtilisateur::insert([
            ['type_utilisateur' => 'COLLABORATEUR'],
            ['type_utilisateur' => 'PRESTATAIRE'],
            ['type_utilisateur' => 'STAGIAIRE'],
        ]);
    }
}
