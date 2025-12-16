<?php

namespace Tests\Feature;

use App\Models\Listes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodosValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Méthode utilitaire pour poster un Todo avec des données par défaut
     */
    private function postTodo(array $overrides = [])
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $liste = Listes::factory()->create();

        $data = array_merge([
            'texte' => 'Acheter du café',
            'date_fin' => now()->addDay()->format('Y-m-d\TH:i'),
            'priority' => 0,              // bouton radio importance
            'categories' => [],             // tableau de catégories (checkbox)
            'liste' => $liste->id,     // correspond à $request->input('liste')
        ], $overrides);

        return $this->post('/action/add', $data);
    }
}
