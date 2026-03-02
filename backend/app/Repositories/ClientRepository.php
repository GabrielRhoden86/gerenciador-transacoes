<?php

namespace App\Repositories;
use App\Models\Client;
use App\Models\Transaction;
use App\Repositories\Contracts\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function all()
    {
        return Client::all();
    }

    public function findById(int $id): Client
    {
        return Client::with('transactions')->findOrFail($id);
    }

    public function getTotalCredits(int $clientId): float
    {
        return Transaction::where('client_id', $clientId)
                    ->where('type', 'credit')
                    ->sum('amount');
    }

    public function getTotalDebits(int $clientId): float
    {
        return Transaction::where('client_id', $clientId)
                    ->where('type', 'debit')
                    ->sum('amount');
    }
}
