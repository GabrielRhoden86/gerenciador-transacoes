<template>
   <v-app-bar flat class="border-b elevation-2">
      <v-app-bar-nav-icon @click="$emit('toggleDrawer')" />
      <v-app-bar-title class="text-blue-grey-darken-2">Sistema de transações</v-app-bar-title>

      <template #append>
      <v-menu>
          <template #activator="{props}">
           <v-avatar v-bind="props">
            <v-img
            src="@/assets/user.jpeg"
            >
            </v-img>
            <v-tooltip
             text="Usuário"
             activator="parent"
             location="bottom"
            />
           </v-avatar>
          </template>

          <v-card min-width="165px" >
          <v-list :list="false" density="compact" nav >
          <v-list-item
          prepend-icon="mdi-logout"
          color="error"
          class="cursor-pointer"
          @click="handleLogout()"
        >
        <!---  @click="suaFuncaoDeLogout"--->

        <v-list-item-title>Sair</v-list-item-title>
      </v-list-item>



          </v-list>
         </v-card>
      </v-menu>

      </template>
    </v-app-bar>

</template>

<script  lang="ts" setup>
  import { useLoadingStore } from '@/stores/loadingStore';
  import { useAuthStore }  from '@/stores/authStore';
  import { useToastStore } from '@/stores/toastStore'
  import { useRouter } from 'vue-router';
  const router = useRouter();
  const loadingStore = useLoadingStore();
  const authStore = useAuthStore();
  const toast = useToastStore();

  defineEmits(['toggleDrawer']);

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
