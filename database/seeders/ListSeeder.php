<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listes = [
            ['libelle' => 'Pro'],
            ['libelle' => 'Famille'],
            ['libelle' => 'Sport'],
            ['libelle' => 'Associatif'],
            // Ajoutez d'autres catégories au besoin
        ];

        // Insertion des données dans la table 'categories'
        DB::table('listes')->insert($listes);
    }
}
