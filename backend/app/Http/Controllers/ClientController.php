<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Http\Requests\StoreClientRequest;

class ClientController extends Controller
{
    protected $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }
    public function store(StoreClientRequest $request)
    {
        $data = $this->service->createClient($request->validated());

        return response()->json([
            'message' => 'Cadastro realizado com sucesso!',
            'data' => $data
        ], 201);
    }

    public function index(Request $request)
    {
        $clients = $this->service->listClients();

        return response()->json([
            'message' => 'Clientes listados com sucesso!',
            'data' => $clients
        ], 200);
    }

    public function show(int $id)
    {
        $data = $this->service->getClientTransactions($id);

        return response()->json([
            'message' => 'Cliente carregado com sucesso!',
            'data' => $data
        ], 200);
    }



}
