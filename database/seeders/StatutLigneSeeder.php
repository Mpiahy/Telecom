<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatutLigne;

class StatutLigneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatutLigne::truncate();
        $statuts = [
            ['id_statut_ligne' => StatutLigne::STATUT_INACTIF, 'statut_ligne' => 'Inactif'],
            ['id_statut_ligne' => StatutLigne::STATUT_EN_ATTENTE, 'statut_ligne' => 'En attente'],
            ['id_statut_ligne' => StatutLigne::STATUT_ATTRIBUE, 'statut_ligne' => 'Attribue'],
            ['id_statut_ligne' => StatutLigne::STATUT_RESILIE, 'statut_ligne' => 'Resilie'],
        ];
        StatutLigne::insert($statuts);
    }
}
