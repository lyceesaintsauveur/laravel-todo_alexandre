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
}
