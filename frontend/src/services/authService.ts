import axiosInstance from '@/api/axiosInstance';
import type { AuthResponse, AuthPayload } from '@/interfaces/auth.interface';


export const authService = {

  async login(email: string, password: string): Promise<AuthResponse> {
    try {
      const payload: AuthPayload = { email, password };
      const response = await axiosInstance.post<AuthResponse>('/login', payload);
      return response.data;
    } catch (error: any) {
      const errorBackend = error.response?.data?.message;
      throw new Error(errorBackend);
    }
  },

  async logout(): Promise<void> {
    await axiosInstance.post('/logout');
  },

};
