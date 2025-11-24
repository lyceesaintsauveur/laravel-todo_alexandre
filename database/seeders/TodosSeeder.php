<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\Models\Todos;

class TodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $todos = [
            "Faire les courses",
            "Arroser les plantes",
            "Répondre aux e-mails",
            "Nettoyer la cuisine",
            "Sortir le chien",
            "Préparer la réunion de lundi"
        ];

        $data = [];

        // assign todos to the first user if exist else create a test user
        $userId = DB::table('users')->value('id');
        if (!$userId) {
            $id = DB::table('users')->insertGetId([
                'name' => 'Seed User',
                'email' => 'seed@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $userId = $id;
        }

        foreach ($todos as $texte) {
            $data[] = [
                'texte' => $texte,
                'termine' => rand(0, 1),
                'important' => rand(0, 1),
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // $data = [
        //        ['texte' => 'texte', 'termine' => 0,'important' => 0],
        //        ['texte' => 'texte 2', 'termine' => 0,'important' => 0]
        //    ];

        DB::table('todos')->insert($data);
    }

}
