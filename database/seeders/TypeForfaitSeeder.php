<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeForfait;
use App\Models\TypeLigne;

class TypeForfaitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeForfait::truncate();
        $types = [
            ['id_type_forfait' => TypeLigne::TYPE_STANDARD, 'type_forfait' => 'Voix et Internet'],
            ['id_type_forfait' => TypeLigne::TYPE_INTERNET, 'type_forfait' => 'Internet'],
            ['id_type_forfait' => TypeLigne::TYPE_FIXE, 'type_forfait' => 'Fixe'],
        ];
        TypeForfait::insert($types);
    }
}
