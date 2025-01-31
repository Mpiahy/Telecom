<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\TypeEquipement;
class TypeEquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeEquipement::truncate();
        $types = [
            ['id_type_equipement' => TypeEquipement::SMARTPHONE, 'type_equipement' => 'Smartphone'],
            ['id_type_equipement' => TypeEquipement::TELEPHONE_TOUCHE, 'type_equipement' => 'Téléphone à Touche'],
            ['id_type_equipement' => TypeEquipement::BOX, 'type_equipement' => 'Box'],
        ];
        TypeEquipement::insert($types);
    }
}