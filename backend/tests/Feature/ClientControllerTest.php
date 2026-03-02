<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_can_create_client_via_api()
    {
        $payload = [
            'name' => 'Novo Cliente',
            'email' => 'cliente@teste.com'
        ];

        $response = $this->postJson('/api/clients', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Cadastro realizado com sucesso!')
                 ->assertJsonStructure(['data' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('clients', ['email' => 'cliente@teste.com']);
    }

    public function test_can_list_all_clients()
    {
        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }
}
