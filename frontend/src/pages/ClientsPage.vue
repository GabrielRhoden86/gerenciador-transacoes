<template>
  <v-container>
    <v-row>
      <v-col>
      <v-card class="pa-5 mt-4">
          <div class="d-flex align-center justify-space-between pa-4">
            <h2 class="text-blue-grey-darken-2 pb-2">Clientes</h2>
              <v-btn @click="isDialog = true" icon>
              <v-icon size="28">
                mdi-account-plus
              </v-icon>
              <v-tooltip
                text="Cadastrar Cliente"
                activator="parent"
                location="bottom"
              >
              </v-tooltip>
              </v-btn>
          </div>
          <Table
          :headers="headers"
          :items="clients"
          @selectItem="handleSelectClient"
          :loading="loadingStore.isLoading"
        />
        </v-card>
      </v-col>
    </v-row>
  </v-container>
   <DialogCreateUser
      v-model="isDialog"
      @createUser="handleCreateClient"
    />
</template>

<script setup  lang="ts">
import { onMounted, ref, computed} from 'vue'
import Table from '@/components/Table.vue';
import DialogCreateUser from '@/components/Dialogs/DialogCreateUser.vue';
import { useClientsStore }  from '@/stores/clientsStore';
import { useToastStore } from '@/stores/toastStore';
import { useLoadingStore } from '@/stores/loadingStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const loadingStore = useLoadingStore();
const toast = useToastStore();
const isDialog = ref(false);
const clientStore = useClientsStore();

const clients = computed(() => {
  if (!clientStore.clients || !Array.isArray(clientStore.clients)) {
    return [];
  }
  return [...clientStore.clients].sort((a, b) => {
    const nameA = a.name || '';
    const nameB = b.name || '';
    return nameA.localeCompare(nameB);
  });
});

const headers = [
  {
    align: 'start',
    key: 'name',
    sortable: false,
    title: 'Nome',
  },
  { key: 'email', title: 'Email' },
  { key: 'created_at', title: 'Data Cadastro' },
  { key: 'actions', title: 'Ações', sortable: false, align: 'end' },
]

onMounted(() => {
  handleListClient();
});

async function handleListClient() {
  loadingStore.isLoading = true;
  try {
    await clientStore.fetchClients();
  } catch (error: any) {
    toast.showMessage(`Erro: ${error.message}`, 'error');
  } finally {
    loadingStore.isLoading = false;
  }
};


async function handleSelectClient(item: any) {
  console.log(`Selecionado client com id: ${item.id}`);
  router.push({
    name: 'cliente',
    params: { id: item.id }
  });
};


async function handleCreateClient(userData: { name: string; email: string }) {
  loadingStore.isLoading = true;
  try {
    await clientStore.createClient(userData.name, userData.email);
        toast.showMessage('Cliente cadastrado com sucesso!', 'success');
  } catch (error: any) {
    const errorMsg = error.response?.data?.message || error.message;
    toast.showMessage(`Erro ao cadastrar: ${errorMsg}`, 'error');
  } finally {
    loadingStore.isLoading = false;
  }
}


</script>

<style>
th{
  background-color: #f0efef;
}

</style>
