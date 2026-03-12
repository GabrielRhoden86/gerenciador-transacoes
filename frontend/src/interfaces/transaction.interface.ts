export interface Transaction{
  id: number;
  client_id: number;
  type: 'credit' | 'debit';
  amount: string;
  description: string;
  created_at: string;
}

export interface SingleClientDetail {
  client: {
    name: string;
    email: string;
  };
  transactions: Transaction[];
  total_credits: number;
  total_debits: number;
  total_balance: number;
}
