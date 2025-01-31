<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forfait;
class ForfaitSeeder extends Seeder
{
    public function run(): void
    {
        // Forfait Yas
        Forfait::insert([
            ['nom_forfait' => 'Forfait 0', 'id_type_forfait' => 1, 'id_operateur' => 34],
            ['nom_forfait' => 'Forfait 1', 'id_type_forfait' => 1, 'id_operateur' => 34],
            ['nom_forfait' => 'Forfait 2', 'id_type_forfait' => 1, 'id_operateur' => 34],
            ['nom_forfait' => 'Forfait 2Bis', 'id_type_forfait' => 1, 'id_operateur' => 34],
            ['nom_forfait' => 'Forfait 3', 'id_type_forfait' => 1, 'id_operateur' => 34],
            ['nom_forfait' => 'Forfait 4', 'id_type_forfait' => 1, 'id_operateur' => 34],
            ['nom_forfait' => 'Forfait 5', 'id_type_forfait' => 1, 'id_operateur' => 34],
        ]);
        // Wifiber Orange
        Forfait::insert([
            ['nom_forfait' => 'Wifiber Pro Start + 12mois', 'id_type_forfait' => 2, 'id_operateur' => 32],
            ['nom_forfait' => 'Wifiber Pro Start + 24mois', 'id_type_forfait' => 2, 'id_operateur' => 32],
            ['nom_forfait' => 'Wifiber Pro Plus + 12mois', 'id_type_forfait' => 2, 'id_operateur' => 32],
            ['nom_forfait' => 'Wifiber Pro Plus + 24mois', 'id_type_forfait' => 2, 'id_operateur' => 32],
        ]);
        // Data Yas
        Forfait::insert([
            ['nom_forfait' => 'Net Month 2.5', 'id_type_forfait' => 2, 'id_operateur' => 34],
            ['nom_forfait' => 'Net Month 15Go', 'id_type_forfait' => 2, 'id_operateur' => 34],
            ['nom_forfait' => 'Net Month 30Go', 'id_type_forfait' => 2, 'id_operateur' => 34],
            ['nom_forfait' => 'Net Month 50Go', 'id_type_forfait' => 2, 'id_operateur' => 34],
            ['nom_forfait' => 'Net Month 250Go', 'id_type_forfait' => 2, 'id_operateur' => 34],
            ['nom_forfait' => 'Net Month 500Go', 'id_type_forfait' => 2, 'id_operateur' => 34],
        ]);
        // Fibre Optique Yas
        Forfait::insert([
            ['nom_forfait' => 'Fibre Optique 45Mbps', 'id_type_forfait' => 3, 'id_operateur' => 34],
            ['nom_forfait' => 'Fibre Optique Platine', 'id_type_forfait' => 3, 'id_operateur' => 34],
            ['nom_forfait' => 'Fibre Optique Gold', 'id_type_forfait' => 3, 'id_operateur' => 34],
            ['nom_forfait' => 'Fibre Optique Home Plus', 'id_type_forfait' => 3, 'id_operateur' => 34],
            ['nom_forfait' => 'Fibre Optique Home', 'id_type_forfait' => 3, 'id_operateur' => 34],
        ]);
    }
}