<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\StatutEquipement;
class StatutEquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatutEquipement::truncate();
        $statuts = [
            ['id_statut_equipement' => StatutEquipement::STATUT_NOUVEAU, 'statut_equipement' => 'Nouveau'],
            ['id_statut_equipement' => StatutEquipement::STATUT_ATTRIBUE, 'statut_equipement' => 'Attribué'],
            ['id_statut_equipement' => StatutEquipement::STATUT_RETOURNE, 'statut_equipement' => 'Retourné'],
            ['id_statut_equipement' => StatutEquipement::STATUT_HS, 'statut_equipement' => 'HS'],
        ];
        StatutEquipement::insert($statuts);
    }
}