<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeLigne;

class TypeLigneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeLigne::truncate();
        $types = [
            ['id_type_ligne' => TypeLigne::TYPE_STANDARD, 'type_ligne' => 'Voix et Internet'],
            ['id_type_ligne' => TypeLigne::TYPE_INTERNET, 'type_ligne' => 'Internet'],
            ['id_type_ligne' => TypeLigne::TYPE_FIXE, 'type_ligne' => 'Fixe'],
        ];
        TypeLigne::insert($types);
    }
}
