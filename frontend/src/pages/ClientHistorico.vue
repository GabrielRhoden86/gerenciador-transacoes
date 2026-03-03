<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card class="mt-4" elevation="2" border>
          <div class="pa-6 bg-grey-lighten-4 d-flex align-center justify-space-between flex-wrap">
            <div class="d-flex align-center">
              <v-avatar color="blue-grey-darken-3" size="48" class="mr-4">
                <v-icon icon="mdi-account" color="white"></v-icon>
              </v-avatar>

              <div>
                <h2 class="text-h5 font-weight-bold text-blue-grey-darken-3 mb-0">
                  Transações
                </h2>

                <div class="d-flex align-center mb-1">
                  <span class="text-subtitle-1 font-weight-medium text-grey-darken-2 mr-3">
                    {{ clientInfo?.name || 'Carregando...' }}
                  </span>
                  <v-chip v-if="clientInfo?.email" size="small" prepend-icon="mdi-email-outline" variant="flat" color="blue-lighten-4">
                    {{ clientInfo.email }}
                  </v-chip>
                </div>

                <div class="d-flex align-center mt-n1">
                  <span class="text-body-2 font-weight-medium text-grey-darken-1 mr-2">
                    Saldo em conta:
                  </span>
                  <span class="text-subtitle-1 font-weight-black text-primary">
                    {{ isVisible ? formatarValor(totalBalance) : '••••••' }}
                  </span>
                  <v-btn
                    :icon="isVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                    variant="text"
                    density="compact"
                    color="grey"
                    class="ml-1"
                    @click="isVisible = !isVisible"
                  ></v-btn>
                </div>
              </div>
            </div>

            <v-btn
              color="primary"
              prepend-icon="mdi-plus"
              @click="IsOpenDialog = true"
              rounded="xl"
              elevation="1"
              class="text-none text-capitalize"
            >
              Nova Transação
            </v-btn>
          </div>

          <v-divider></v-divider>

          <div class="pa-5">
            <Table
              :headers="headers"
              :items="sortedClients"
              :loading="loadingStore.isLoading"
            />
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>

  <DialogCreateTransactions
  v-model="IsOpenDialog"
  @createTransaction="handleCreateTransaction"
/>
</template>

<script setup  lang="ts">
import { onMounted, ref, computed } from 'vue'
import Table from '@/components/Table.vue';
import DialogCreateTransactions from '@/components/Dialogs/DialogCreateTransactions.vue';
import { useClientsStore } from '@/stores/clientsStore';
import { useToastStore } from '@/stores/toastStore';
import { useLoadingStore } from '@/stores/loadingStore';
import { formatarValor } from '@/Utils/formatters';
import { useRoute } from 'vue-router';

const route = useRoute();
const loadingStore = useLoadingStore();
const toast = useToastStore();
const clientStore = useClientsStore();
const clientInfo = ref<{ name: string; email: string } | null>(null);
const isVisible = ref(false);
const IsOpenDialog = ref(false);
const totalBalance = computed(() => clientStore.selectedClientInfo?.total_balance || 0);

const headers = [
  { align: 'start', key: 'id', title: 'ID', sortable: true },
  { key: 'amount', title: 'Valor' },
  { key: 'type', title: 'Tipo' },
  { key: 'description', title: 'Descrição' },
  { key: 'created_at', title: 'Data' },
];

onMounted(() => {
  const idDaUrl = route.params.id;
  handleShowClient(Number(idDaUrl));
});

  const sortedClients = computed(() => {
    const list = clientStore.selectedClientTransactions;
    if (!list || list.length === 0) {
      return [];
    }
    return [...list].sort((a, b) =>
      new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
    );
  });

async function handleShowClient(id: number) {
  loadingStore.isLoading = true;
  try {
    const response = await clientStore.searchClient(id);
    if (response) {
      clientInfo.value = response.client;
    }
  } catch (error: any) {
    toast.showMessage(`Erro ao carregar cliente: ${error.message}`, 'error');
  } finally {
    loadingStore.isLoading = false;
  }
}

async function handleCreateTransaction(data: {
    amount: number;
    type: 'credit' | 'debit';
    description: string;
  }) {
  loadingStore.isLoading = true;
  try {
    await clientStore.executeTransaction(Number(route.params.id), data.type, data.amount, data.description);
    toast.showMessage('Transação criada com sucesso!', 'success');
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
