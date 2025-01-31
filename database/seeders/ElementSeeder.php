<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Element;
class ElementSeeder extends Seeder
{
    public function run(): void
    {
        Element::insert([
            ['libelle' => 'Appel Flotte initial', 'unite' => 'Heures', 'prix_unitaire_element' => 2160],
            ['libelle' => 'Appel Flotte supplémentaire', 'unite' => 'Heures', 'prix_unitaire_element' => 3600],
            ['libelle' => 'Appel Tout TELMA', 'unite' => 'Heures', 'prix_unitaire_element' => 4000],
            ['libelle' => 'Appel Tout MADA', 'unite' => 'Heures', 'prix_unitaire_element' => 9000],
            ['libelle' => 'Appel vers Etranger', 'unite' => 'Heures', 'prix_unitaire_element' => 10000],
            ['libelle' => 'DATA', 'unite' => '15 Go', 'prix_unitaire_element' => 62500],
            ['libelle' => 'SMS', 'unite' => '100 SMS', 'prix_unitaire_element' => 7500],
        ]);
        Element::insert([
            ['libelle' => 'Wifiber Pro Start + 12mois', 'unite' => '1Mbps/7Mbps', 'prix_unitaire_element' => 199000],
            ['libelle' => 'Wifiber Pro Start + 24mois', 'unite' => '1Mbps/7Mbps', 'prix_unitaire_element' => 179000],
            ['libelle' => 'Wifiber Pro Plus + 12mois', 'unite' => '2Mbps/15Mbps', 'prix_unitaire_element' => 779000],
            ['libelle' => 'Wifiber Pro Plus + 24mois', 'unite' => '2Mbps/15Mbps', 'prix_unitaire_element' => 679000],
        ]);
        Element::insert([
            ['libelle' => 'Data 2.5Go', 'unite' => '2.5Go', 'prix_unitaire_element' => 15000],
            ['libelle' => 'Data 15Go', 'unite' => '15Go', 'prix_unitaire_element' => 55000],
            ['libelle' => 'Data 30Go', 'unite' => '30Go', 'prix_unitaire_element' => 100000],
            ['libelle' => 'Data 50Go', 'unite' => '50Go', 'prix_unitaire_element' => 150000],
            ['libelle' => 'Data 250Go', 'unite' => '250Go', 'prix_unitaire_element' => 24500],
            ['libelle' => 'Data 500Go', 'unite' => '500Go', 'prix_unitaire_element' => 375834],
        ]);
        Element::insert([
            ['libelle' => 'Fibre Optique 45Mbps', 'unite' => '45Mbps/200Mbps', 'prix_unitaire_element' => 38860000],
            ['libelle' => 'Fibre Optique Platine', 'unite' => '1Mbps/200Mbps', 'prix_unitaire_element' => 2580000],
            ['libelle' => 'Fibre Optique Gold', 'unite' => '512Kbps/100Mbps', 'prix_unitaire_element' => 1080000],
            ['libelle' => 'Fibre Optique Plus', 'unite' => '100Mbps', 'prix_unitaire_element' => 377500],
            ['libelle' => 'Fibre Optique Home', 'unite' => '100Mbps', 'prix_unitaire_element' => 215834],
        ]);
    }
}