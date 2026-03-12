// Objeto puro do Cliente
export interface Client {
  id: number;
  name: string;
  email: string;
  created_at: string;
}

export interface Transaction {
  id: number;
  amount: number;
  type: string;
  description: string;
  created_at: string;
}

export interface SingleClientResponse {
  message: string;
  data: {
    client: Client;
    transactions: Transaction[];
    total_balance: number;
  };
}

export interface CreateClientResponse {
  message: string;
  data: Client;
}

export interface ClientsListResponse {
  data: Client[];
}
