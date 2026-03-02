<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ClientService;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Mockery;
use App\Models\Client;

class ClientServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close(); // Finaliza os mocks e remove os handlers de erro
        parent::tearDown();
    }
    public function test_it_calculates_client_balance_correctly()
    {
        $repository = Mockery::mock(ClientRepositoryInterface::class);

        // Criamos um objeto de cliente real (mas sem salvar no banco)
        $client = new Client(['name' => 'John Doe', 'email' => 'john@example.com']);

        // Simulamos a relação de transações
        $client->setRelation('transactions', collect([
            (object)['amount' => 100, 'type' => 'credit', 'created_at' => now()],
        ]));

        // Definimos o que o Repository deve retornar
        $repository->shouldReceive('findById')->once()->with(1)->andReturn($client);
        $repository->shouldReceive('getTotalCredits')->once()->with(1)->andReturn(100.0);
        $repository->shouldReceive('getTotalDebits')->once()->with(1)->andReturn(40.0);

        $service = new ClientService($repository);
        $result = $service->getClientTransactions(1);

        // Asserções
        $this->assertEquals(60.0, $result['total_balance']);
        $this->assertEquals('John Doe', $result['client']['name']);
    }
}
