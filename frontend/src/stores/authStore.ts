import { defineStore } from 'pinia';
import { ref  } from 'vue';
import { authService } from '@/services/authService';


export const useAuthStore = defineStore('auth', () => {

  const token = ref<string | null>(localStorage.getItem('token'));

  async function login(email: string, password: string) {
    const result = await authService.login(email, password);

      if (result.token) {
        token.value = result.token;
        localStorage.setItem('token', result.token);
      }
  }

  function logout() {
    token.value = null;
    localStorage.removeItem('token');
  }

  return { token, login, logout };
});
