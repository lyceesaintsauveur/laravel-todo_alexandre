<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\Listes;
use App\Models\Todos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodosModelTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_un_todos_appartient_a_une_liste()
    {
        // Arrange : création d'une liste
        $liste = Listes::factory()->create();

        // Act : création d'un Todos lié à cette liste
        $todo = Todos::factory()->for($liste, 'listes')->create();

        // Assert : la relation renvoie bien la bonne liste
        $this->assertTrue($todo->listes->is($liste));
    }

    public function test_un_todos_appartient_a_un_utilisateur()
    {
        // Arrange : création d'un utilisateur
        $user = User::factory()->create();

        // Act : création d'un Todos lié à cet utilisateur
        $todo = Todos::factory()->for($user, 'user')->create();

        // Assert : la relation renvoie bien le bon utilisateur
        $this->assertTrue($todo->user->is($user));
    }

    public function test_un_todos_est_non_termine_et_non_important_par_defaut()
    {
        // Act : création d'un Todos sans préciser termine/important
        $todo = Todos::factory()->create();

        // Assert : les valeurs par défaut sont respectées
        $this->assertFalse($todo->termine);
        $this->assertFalse($todo->important);
    }
}
