<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Client;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function credit(StoreTransactionRequest $request, Client $client)
    {
        $validated = $request->validated();
        $transaction = $this->service->credit(
            $client,
            $validated['amount'],
            $validated['description'] ?? null
        );

        return response()->json($transaction, 201);
    }

    public function debit(StoreTransactionRequest $request, Client $client)
    {
      try{

        $validated = $request->validated();
        $transaction = $this->service->debit(
            $client,
            $validated['amount'],
            $validated['description'] ?? null
        );
        return response()->json($transaction, 201);

      } catch (\DomainException $e) {
        return response()->json(['message' => $e->getMessage()], 422);
      }
    }
}
