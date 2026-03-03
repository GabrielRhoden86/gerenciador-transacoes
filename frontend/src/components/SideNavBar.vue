<template>
    <v-navigation-drawer temporary >
    <v-app-bar-nav-icon
      @click="$emit('update:modelValue', false)"
      class="mt-2 ml-1"
    />
    <v-list>

      <v-list-item
        title="Clientes"
        class="cursor-pointer"
        prepend-icon="mdi-account"
        :to="{name: 'clientes'}"
      />
      <v-list-item
        title="Logout"
        class="cursor-pointer"
        prepend-icon="mdi-logout"
        @click="handleLogout()"
      />
    </v-list>
   </v-navigation-drawer>
</template>

<script lang="ts" setup>
import { useRoute } from 'vue-router';
import { watch } from 'vue';
import { useAuthStore }  from '@/stores/authStore';
import { useToastStore } from '@/stores/toastStore'
import { useLoadingStore } from '@/stores/loadingStore';
import { useRouter } from 'vue-router';
const router = useRouter();
const loadingStore = useLoadingStore();
const authStore = useAuthStore();
const toast = useToastStore();


const route = useRoute();
const emit = defineEmits(['update:modelValue']);

watch(
  () => route.fullPath,
  () => {
    emit('update:modelValue', false);
  }
);

  async function handleLogout() {
    loadingStore.isLoading = true;
    try {
      authStore.logout();
      toast.showMessage('Logout efetuado com sucesso!', 'success');
      router.push({ name: 'login' });
    } catch (error: any) {
      const errorMenssage = error.message;
      toast.showMessage(`Erro ao efetuar logout: ${errorMenssage}`, 'error');
    }finally{
      loadingStore.isLoading = false;
    }
  }
</script>
