import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useClientsService } from '@/services/clientsService';
import type { Client, Transaction} from '@/interfaces/clients.interface';

export const useClientsStore = defineStore('clients', () => {
  const clients = ref<Client[]>([]);
  const selectedClientTransactions = ref<Transaction[]>([]);
  const selectedClientInfo = ref<any>(null);

  const fetchClients = async () => {
    const response = await useClientsService.getClients();
    clients.value = response.data;
    return clients.value;
  };

 const searchClient = async (id: number) => {
  const response = await useClientsService.showClient(id);
  selectedClientTransactions.value = response.data.transactions;
  selectedClientInfo.value = response.data;
  return response.data;
};

  const createClient = async (name: string, email: string) => {
    const response = await useClientsService.createClient(name, email);
    const newClient = response.data|| response.data;
    clients.value.push(newClient);
    return newClient;
  };

const executeTransaction = async (id: number, type: string, amount: number, description: string) => {
  const newTransaction = await useClientsService.createTransaction(id, type, amount, description);
  selectedClientTransactions.value.push(newTransaction);
  await searchClient(id);
  return newTransaction;
};


  return {
    clients,
    fetchClients,
    createClient,
    searchClient,
    selectedClientTransactions,
    selectedClientInfo,
    executeTransaction
  };

});
