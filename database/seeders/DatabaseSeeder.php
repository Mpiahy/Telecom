<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(TypeUtilisateurSeeder::class);
        $this->call(OperateurSeeder::class);
        $this->call(ContactOperateurSeeder::class);
        $this->call(StatutEquipementSeeder::class);
        $this->call(TypeEquipementSeeder::class);
        $this->call(TypeForfaitSeeder::class);
        $this->call(ElementSeeder::class);
        $this->call(ForfaitSeeder::class);
        $this->call(ForfaitElementSeeder::class);
        $this->call(StatutLigneSeeder::class);
        $this->call(TypeLigneSeeder::class);
    }
}