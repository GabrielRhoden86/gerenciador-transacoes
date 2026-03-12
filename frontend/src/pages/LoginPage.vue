<template>
  <div class="mt-12">
    <v-form @submit.prevent="handleLogin" class="form">
    <v-card
      class="mx-auto pa-5 mt-12"
      elevation="8"
      max-width="448"
      rounded="lg"
    >
      <v-img
      class="mx-auto my-4 "
      max-width="300"
      src="@/assets/logo-app.png"
      />
      <div class="text-subtitle-1 text-medium-emphasis">Email</div>

      <v-text-field
        v-model="email"
        density="compact"
        placeholder="Endereço de e-mail"
        prepend-inner-icon="mdi-email-outline"
        variant="outlined"
      />

      <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
        Senha
        <a
          class="text-caption text-decoration-none text-blue"
          href="#"
          rel="noopener noreferrer"
          target="_blank"
        >
          Esqueceu sua senha ?</a>
      </div>

      <v-text-field
        v-model="password"
        :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'"
        density="compact"
        placeholder="Entre com sua senha"
        prepend-inner-icon="mdi-lock-outline"
        variant="outlined"
        @click:append-inner="visible = !visible"
      />

      <v-btn
        :disabled="loadingStore.isLoading "
        class="mb-8"
        color="blue"
        size="large"
        variant="tonal"
        block
        type="submit"
      >
      <v-progress-circular v-if="loadingStore.isLoading "
        color="primary"
        :size="25"
        indeterminate
        class="mr-2"
      />
        Entrar
      </v-btn>

      <v-card-text class="text-center">
        <a
          class="text-blue text-decoration-none"
          href="#"
          rel="noopener noreferrer"
          target="_blank"
        >
        </a>
      </v-card-text>
    </v-card>
    </v-form>

  </div>
</template>

<script lang="ts" setup>

  import {ref } from 'vue';
  import { useAuthStore }  from '@/stores/authStore';
  import { useToastStore } from '@/stores/toastStore'
  import { useLoadingStore } from '@/stores/loadingStore'
  import { useRouter } from 'vue-router';

  const authStore = useAuthStore();
  const toast = useToastStore();
  const loadingStore = useLoadingStore();
  const email = ref(<string>'');
  const password = ref(<string>'');
  const visible = ref(<boolean>false);
  const router = useRouter();

  async function handleLogin(e: Event) {
    e.preventDefault();
    loadingStore.isLoading = true;
    try {
      await authStore.login(email.value, password.value);
      toast.showMessage('Bem-vindo!', 'success');
      router.push({ name: 'clientes' });
    } catch (error: any) {
      const errorMenssage = error;
      console.error('Erro ao efetuar login:', error);
      toast.showMessage(`Erro ao efetuar login: ${errorMenssage}`, 'error');
    }finally{
      loadingStore.isLoading = false;
    }
  }

</script>

<style scoped>
.form {
  max-width: 548px;
  height: 100vh;
  margin: 0 auto;
  margin-top: 5%;
}
</style>
