import axiosInstance from '@/api/axiosInstance';
import type { ClientsListResponse, SingleClientResponse, CreateClientResponse, Transaction } from '@/interfaces/clients.interface';

export const useClientsService = {

  async getClients(): Promise<ClientsListResponse> {
    try {
      const response = await axiosInstance.get<ClientsListResponse>('/clients');
      return response.data;
    } catch (error: any) {
      const errorBackend = error.response?.data?.message;
      throw new Error(errorBackend);
    }
  },

  async showClient(id: number): Promise<SingleClientResponse> {
    try {
      const response = await axiosInstance.get<SingleClientResponse>(`/clients/${id}`);
      return response.data;
    } catch (error: any) {
      const errorBackend = error.response?.data?.message;
      throw new Error(errorBackend);
    }
  },

  async createClient(name: string, email: string): Promise<CreateClientResponse> {
    try {
      const response = await axiosInstance.post<CreateClientResponse>('/clients',{ name, email });
      return response.data;
    } catch (error: any) {
      const errorBackend = error.response?.data?.message;
      throw new Error(errorBackend);
      }
  },

  async createTransaction(id: number, type: string, amount:number, description:string): Promise<Transaction> {
    try {
      const response = await axiosInstance.post<Transaction>(`/transactions/${id}/${type}`, { amount, description });
      return response.data;
    } catch (error: any) {
      const errorBackend = error.response?.data?.message;
      throw new Error(errorBackend);
      }
  },

};
