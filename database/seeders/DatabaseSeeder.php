<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        // On demande à Laravel d'exécuter ton seeder spécifique
        $this->call([
            DataCenterSeeder::class
        ]);
    }
}