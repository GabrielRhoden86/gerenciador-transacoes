<?php
namespace App\Services;
use App\Repositories\Contracts\ClientRepositoryInterface;

class ClientService
{
    protected ClientRepositoryInterface $repository;

    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createClient(array $data)
    {
        return $this->repository->create($data);
    }

    public function listClients()
    {
        return $this->repository->all();
    }


    public function getClientTransactions(int $id): array
    {
        $client = $this->repository->findById($id);
        $transactions = $client->transactions->sortByDesc('created_at');

        $totalCredits = $this->repository->getTotalCredits($id);
        $totalDebits  = $this->repository->getTotalDebits($id);

        $totalBalance = $totalCredits - $totalDebits;

       return [
            'client' => [
                'name' => $client->name,
                'email' => $client->email,
            ],
             //->values() organiza os indices após a ordenação feita pelo sortByDesc
            'transactions' => $transactions->values(),
            'total_credits' => $totalCredits,
            'total_debits' => $totalDebits,
            'total_balance' => $totalBalance,

        ];
    }

}
