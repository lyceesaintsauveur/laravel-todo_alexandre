<?php

namespace Tests\Feature;

use App\Models\Todos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodosSuppressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_ne_peut_pas_supprimer_un_todo()
    {
        $todo = Todos::factory()->create();

        $response = $this->delete(route('todo.delete', $todo->id));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
    }

    public function test_utilisateur_peut_supprimer_son_todo_via_route_delete()
    {
        $user = User::factory()->create();
        $todo = Todos::factory()->create(['user_id' => $user->id, 'termine' => 1]);

        $response = $this->actingAs($user)->delete(route('todo.delete', $todo->id));

        $response->assertRedirect(route('todo.liste'));
        $this->assertSoftDeleted('todos', ['id' => $todo->id]);
    }

    public function test_route_todo_delete_n_accepte_pas_la_methode_post()
    {
        $user = User::factory()->create();
        $todo = Todos::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('todo.delete', $todo->id));

        $response->assertStatus(405);
        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
    }
}
