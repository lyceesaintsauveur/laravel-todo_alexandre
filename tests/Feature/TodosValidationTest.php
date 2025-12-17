<?php

namespace Tests\Feature;

use App\Models\Listes;
use App\Models\Todos;
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

    //     public function test_texte_est_obligatoire()
    // {
    //     $response = $this->postTodo(['texte' => '']);

    //     $response->assertSessionHasErrors('texte');
    //     $this->assertDatabaseCount('todos', 0);
    // }

    public function test_texte_est_obligatoire()
    {
        $response = $this->postTodo(['texte' => '']);
        $response->assertRedirect(route('todo.liste'));
        $response->assertSessionHas('message', 'Erreur dans la saisie du texte');
        $this->assertDatabaseCount('todos', 0);
    }

    public function test_texte_doit_avoir_une_longueur_minimale()
    {
        $response = $this->postTodo(['texte' => 'ab']); // 2 caractères

        $response->assertSessionHas('message', 'Erreur dans la saisie du texte');
        $this->assertDatabaseCount('todos', 0);
    }

    public function test_texte_doit_avoir_une_longueur_maximale()
    {
        $texteTropLong = str_repeat('a', 257); // 256 caractères

        $response = $this->postTodo([
            'texte' => $texteTropLong,
        ]);

        $response->assertSessionHas('message', 'Erreur dans la saisie du texte');
        $this->assertDatabaseCount('todos', 0);
    }

    public function test_un_todos_valide_est_cree()
    {
        $response = $this->postTodo(); // toutes les valeurs par défaut sont valides

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(); // ou ->assertRedirect('/'); selon ton contrôleur

        $this->assertDatabaseCount('todos', 1);

        $this->assertDatabaseHas('todos', [
            'texte' => 'Acheter du café',
            'termine' => 0,
            'important' => 0,
        ]);
    }

    public function test_voir_la_connexion_de_test()
    {
        $this->assertSame('mysql', config('database.default'));
        $this->assertSame('todo_test', config('database.connections.mysql.database'));
    }

    public function test_invite_ne_peut_pas_acceder_aux_todos()
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));

    }

    // public function test_un_utilisateur_ne_peut_pas_modifier_le_todo_d_un_autre()
    // {
    //     $a = User::factory()->create();
    //     $b = User::factory()->create();
    //     $todo = Todos::factory()->for($a, 'user')->create();

    //     $response = $this->actingAs($b)->post("/todos/{$todo->id}/edit", [
    //         'texte' => 'H4ck',
    //     ]);

    //     $response->assertForbidden();
    // }
}
