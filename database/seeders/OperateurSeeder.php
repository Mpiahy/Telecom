<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operateur;

class OperateurSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
public function run(): void
    {

        Operateur::truncate();

        Operateur::insert([
            ['id_operateur' => 34, 'nom_operateur' => 'Yas'],
            ['id_operateur' => 32, 'nom_operateur' => 'Orange'],
            // ['id_operateur' => 33, 'nom_operateur' => 'Airtel'],
            // ['id_operateur' => 7, 'nom_operateur' => 'Starlink'],
        ]);
    }
}