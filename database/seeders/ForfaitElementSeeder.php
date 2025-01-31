<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForfaitElement;
class ForfaitElementSeeder extends Seeder
{
    public function run(): void
    {
        ForfaitElement::insert([
            // Forfait 0
            ['id_element' => 1, 'id_forfait' => 1, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 1, 'quantite' => 0],
            ['id_element' => 3, 'id_forfait' => 1, 'quantite' => 0],
            ['id_element' => 4, 'id_forfait' => 1, 'quantite' => 0],
            ['id_element' => 5, 'id_forfait' => 1, 'quantite' => 0],
            ['id_element' => 6, 'id_forfait' => 1, 'quantite' => 0],
            ['id_element' => 7, 'id_forfait' => 1, 'quantite' => 1],
            // Forfait 1
            ['id_element' => 1, 'id_forfait' => 2, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 2, 'quantite' => 0],
            ['id_element' => 3, 'id_forfait' => 2, 'quantite' => 2],
            ['id_element' => 4, 'id_forfait' => 2, 'quantite' => 1],
            ['id_element' => 5, 'id_forfait' => 2, 'quantite' => 0],
            ['id_element' => 6, 'id_forfait' => 2, 'quantite' => 0],
            ['id_element' => 7, 'id_forfait' => 2, 'quantite' => 1],
            // Forfait 2
            ['id_element' => 1, 'id_forfait' => 3, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 3, 'quantite' => 0],
            ['id_element' => 3, 'id_forfait' => 3, 'quantite' => 5],
            ['id_element' => 4, 'id_forfait' => 3, 'quantite' => 2],
            ['id_element' => 5, 'id_forfait' => 3, 'quantite' => 0],
            ['id_element' => 6, 'id_forfait' => 3, 'quantite' => 0],
            ['id_element' => 7, 'id_forfait' => 3, 'quantite' => 1],
            // Forfait 2Bis
            ['id_element' => 1, 'id_forfait' => 4, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 4, 'quantite' => 10],
            ['id_element' => 3, 'id_forfait' => 4, 'quantite' => 5],
            ['id_element' => 4, 'id_forfait' => 4, 'quantite' => 3],
            ['id_element' => 5, 'id_forfait' => 4, 'quantite' => 0],
            ['id_element' => 6, 'id_forfait' => 4, 'quantite' => 1],
            ['id_element' => 7, 'id_forfait' => 4, 'quantite' => 1],
            // Forfait 3
            ['id_element' => 1, 'id_forfait' => 5, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 5, 'quantite' => 10],
            ['id_element' => 3, 'id_forfait' => 5, 'quantite' => 5],
            ['id_element' => 4, 'id_forfait' => 5, 'quantite' => 3],
            ['id_element' => 5, 'id_forfait' => 5, 'quantite' => 1],
            ['id_element' => 6, 'id_forfait' => 5, 'quantite' => 1],
            ['id_element' => 7, 'id_forfait' => 5, 'quantite' => 1],
            // Forfait 4
            ['id_element' => 1, 'id_forfait' => 6, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 6, 'quantite' => 20],
            ['id_element' => 3, 'id_forfait' => 6, 'quantite' => 3],
            ['id_element' => 4, 'id_forfait' => 6, 'quantite' => 3],
            ['id_element' => 5, 'id_forfait' => 6, 'quantite' => 2],
            ['id_element' => 6, 'id_forfait' => 6, 'quantite' => 1],
            ['id_element' => 7, 'id_forfait' => 6, 'quantite' => 2],
            // Forfait 5
            ['id_element' => 1, 'id_forfait' => 7, 'quantite' => 5],
            ['id_element' => 2, 'id_forfait' => 7, 'quantite' => 10],
            ['id_element' => 3, 'id_forfait' => 7, 'quantite' => 4],
            ['id_element' => 4, 'id_forfait' => 7, 'quantite' => 2],
            ['id_element' => 5, 'id_forfait' => 7, 'quantite' => 2],
            ['id_element' => 6, 'id_forfait' => 7, 'quantite' => 1],
            ['id_element' => 7, 'id_forfait' => 7, 'quantite' => 3],
        ]);

        ForfaitElement::insert([
            // Wifiber Pro Start + 12mois
            ['id_element' => 8, 'id_forfait' => 8, 'quantite' => 1],
            // Wifiber Pro Start + 24mois
            ['id_element' => 9, 'id_forfait' => 9, 'quantite' => 1],
            // Wifiber Pro Plus + 12mois
            ['id_element' => 10, 'id_forfait' => 10, 'quantite' => 1],
            // Wifiber Pro Plus + 24mois
            ['id_element' => 11, 'id_forfait' => 11, 'quantite' => 1],
        ]);

        ForfaitElement::insert([
            // Data Yas
            ['id_element' => 12, 'id_forfait' => 12, 'quantite' => 1],
            ['id_element' => 13, 'id_forfait' => 13, 'quantite' => 1],
            ['id_element' => 14, 'id_forfait' => 14, 'quantite' => 1],
            ['id_element' => 15, 'id_forfait' => 15, 'quantite' => 1],
            ['id_element' => 16, 'id_forfait' => 16, 'quantite' => 1],
            ['id_element' => 17, 'id_forfait' => 17, 'quantite' => 1],
        ]);

        ForfaitElement::insert([
            // Fibre Optique 45Mbps
            ['id_element' => 18, 'id_forfait' => 18, 'quantite' => 1],
            // Fibre Optique Platine
            ['id_element' => 19, 'id_forfait' => 19, 'quantite' => 1],
            // Fibre Optique Gold
            ['id_element' => 20, 'id_forfait' => 20, 'quantite' => 1],
            // Fibre Optique Home Plus
            ['id_element' => 21, 'id_forfait' => 21, 'quantite' => 1],
            // Fibre Optique Home
            ['id_element' => 22, 'id_forfait' => 22, 'quantite' => 1],
        ]);
        
    }
}