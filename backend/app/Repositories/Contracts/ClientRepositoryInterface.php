<?php

namespace App\Repositories\Contracts;

use App\Models\Client;

interface ClientRepositoryInterface
{
    public function create(array $data): Client;
    public function all();
    public function findById(int $id): Client;
    public function getTotalCredits(int $clientId): float;
    public function getTotalDebits(int $clientId): float;

}
