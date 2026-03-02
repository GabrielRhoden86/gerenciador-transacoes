<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TransactionService;
use App\Models\Client;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Mockery;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{

    use RefreshDatabase; // Isso vai criar as tabelas no SQLite em memória

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


    public function test_it_throws_exception_when_balance_is_insufficient()
    {
        // 1. Mock do Repositório (para a criação da transação de débito que falhará)
        $repository = Mockery::mock(TransactionRepositoryInterface::class);

        // 2. Criar um cliente real no banco de memória (usando SQLite do teste)
        // Não passamos 'balance' aqui, pois ele é calculado!
        $client = Client::factory()->create();

        // 3. Inserir um CRÉDITO real no banco para dar saldo ao cliente
        // Isso fará com que o getBalanceAttribute() retorne 50.0
        Transaction::create([
            'client_id' => $client->id,
            'amount' => 50.0,
            'type' => 'credit',
            'description' => 'Saldo Inicial'
        ]);

        $service = new TransactionService($repository);

        // 4. Expectativa de erro
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Saldo insuficiente para débito.');

        // 5. Execução: Tentar debitar 100 de quem só tem 50
        // O service chamará $client->refresh()->balance, que agora retornará 50.0 corretamente
        $service->debit($client, 100.0, 'Compra Negada');
    }
    public function test_it_creates_credit_transaction_successfully()
    {
        $repository = Mockery::mock(TransactionRepositoryInterface::class);
        $client = new Client(['id' => 1]);

        $repository->shouldReceive('create')
            ->once()
            ->with(Mockery::subset(['type' => 'credit', 'amount' => 100.0]))
            ->andReturn(new Transaction());

        $service = new TransactionService($repository);
        $service->credit($client, 100.0, 'Depósito');

        $this->assertTrue(true); // Se não estourou erro e rodou o 'once', passou.
    }
}
