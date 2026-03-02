<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $repository;

    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function credit(Client $client, float $amount, string $description)
    {
        return $this->repository->create([
            'client_id'   => $client->id,
            'type'        => 'credit',
            'amount'      => $amount,
            'description' => $description,
        ]);
    }

    public function debit(Client $client, float $amount, string $description)
    {
        //garantir a atomicidade é importante em operações financeiras
        return DB::transaction(function () use ($client, $amount, $description) {

            //Obter o saldo atualizado: garantia de segurança contra dados "sujos" na memória
            if ($client->refresh()->balance < $amount) {
                throw new DomainException('Saldo insuficiente para débito.');
            }

            return $this->repository->create([
                'client_id'   => $client->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => $description,
            ]);
        });
    }
}
