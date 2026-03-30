<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AccueilTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_invite_est_redirige_depuis_accueil_vers_login()
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_utilisateur_auth_peut_acceder_a_l_accueil()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->get('/');

        $response->assertOk();
        // On vérifiera qu'on voit bien le texte "Ma Todo List" dans la page
        $response->assertSee('Ma Todo List');
    }

    // public function test_filtre_de_todos_est_affiche_et_contient_les_boutons_attendus()
    // {
    //     $user = User::factory()->create();

    //     // Créer deux todos pour que le markup aie des statuts termines/non termines
    //     $user->todos()->create(['texte' => 'Todo en cours', 'termine' => 0, 'important' => 0]);
    //     $user->todos()->create(['texte' => 'Todo terminée', 'termine' => 1, 'important' => 0]);

    //     $response = $this->actingAs($user)
    //         ->followingRedirects()
    //         ->get('/');

    //     $response->assertOk();
    //     $response->assertSee('data-filter="all"', false);
    //     $response->assertSee('data-filter="pending"', false);
    //     $response->assertSee('data-filter="done"', false);
    //     $response->assertSee('data-termine="0"', false);
    //     $response->assertSee('data-termine="1"', false);
    // }

    public function test_filtre_de_status_via_route_et_query_string()
    {
        $user = User::factory()->create();

        $user->todos()->create(['texte' => 'Todo en cours', 'termine' => 0, 'important' => 0]);
        $user->todos()->create(['texte' => 'Todo terminée', 'termine' => 1, 'important' => 0]);

        $this->actingAs($user);

        $responsePending = $this->get(route('todo.liste.status', 'pending'));
        $responsePending->assertOk();
        $responsePending->assertSee('Todo en cours');
        $responsePending->assertDontSee('Todo terminée');

        $responseDone = $this->get(route('todo.liste.status', 'done'));
        $responseDone->assertOk();
        $responseDone->assertSee('Todo terminée');
        $responseDone->assertDontSee('Todo en cours');

        $responseAll = $this->get(route('todo.liste.status', 'all'));
        $responseAll->assertOk();
        $responseAll->assertSee('Todo en cours');
        $responseAll->assertSee('Todo terminée');

        $responseQuery = $this->get('/?status=done');
        $responseQuery->assertOk();
        $responseQuery->assertSee('Todo terminée');
        $responseQuery->assertDontSee('Todo en cours');
    }
}
