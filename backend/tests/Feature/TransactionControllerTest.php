<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_add_credit_to_client()
    {
        // Ajustado para a coluna 'role'
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $this->actingAs($admin, 'sanctum');

        $payload = [
            'amount' => 150.50,
            'description' => 'Ajuste de saldo'
        ];

        $response = $this->postJson("/api/transactions/{$client->id}/credit", $payload);

        $response->assertStatus(201);
    }

    public function test_non_admin_cannot_add_credit()
    {
        // Usuário comum
        $user = User::factory()->create(['role' => 'user']);
        $client = Client::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/transactions/{$client->id}/credit", ['amount' => 10]);

        $response->assertStatus(403);
    }
}
