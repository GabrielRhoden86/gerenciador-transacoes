<template>
  <v-dialog
    width="600px"
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
  >
    <v-card>
      <v-card-title class="text-blue-grey-darken-2">
        Nova Transação
      </v-card-title>

      <v-divider />

      <v-form ref="formRef" @submit.prevent="submitForm">
        <v-card-text class="mt-2">
          <v-row>
            <v-col cols="12">
              <v-select
                v-model="formData.type"
                label="Tipo da transação"
                :items="transactionTypes"
                item-title="label"
                item-value="value"
                variant="outlined"
                density="compact"
                :rules="[v => !!v || 'Selecione o tipo']"
              />
            </v-col>

            <v-fade-transition hide-on-leave>
              <v-col cols="12" v-if="formData.type === 'debit'">
                <v-label class="mb-2">Selecione os Serviços</v-label>
                <v-list lines="one" variant="outlined" class="rounded-lg border">
                  <v-list-item
                    v-for="service in availableServices"
                    :key="service.id"
                    @click="toggleService(service)"
                  >
                    <template v-slot:prepend>
                      <v-checkbox-btn
                        :model-value="isServiceSelected(service)"
                        color="primary"
                      ></v-checkbox-btn>
                    </template>

                    <v-list-item-title>{{ service.text }}</v-list-item-title>
                    <v-list-item-subtitle>R$ {{ service.price.toFixed(2) }}</v-list-item-subtitle>

                    <template v-slot:append>
                      <v-icon :icon="service.icon" size="small" color="grey-lighten-1"></v-icon>
                    </template>
                  </v-list-item>
                </v-list>

                <div class="text-right mt-2 text-subtitle-1 font-weight-bold">
                  Total Selecionado: R$ {{ calculatedTotal.toFixed(2) }}
                </div>
              </v-col>
            </v-fade-transition>

            <v-fade-transition hide-on-leave>
              <v-row v-if="formData.type === 'credit'" class="ma-0 pa-0 w-100">
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model.number="formData.amount"
                    label="Valor"
                    prefix="R$"
                    type="number"
                    variant="outlined"
                    density="compact"
                    :rules="amountRules"
                  />
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="formData.description"
                    label="Descrição"
                    variant="outlined"
                    density="compact"
                    :rules="descriptionRules"
                  />
                </v-col>
              </v-row>
            </v-fade-transition>
          </v-row>
        </v-card-text>

        <v-divider />

        <v-card-actions class="pa-4">
          <v-spacer />
          <v-btn variant="text" @click="closeDialog">Cancelar</v-btn>
          <v-btn
            type="submit"
            color="primary"
            variant="tonal"
            :disabled="formData.type === 'debit' && selectedServices.length === 0"
          >
            Salvar
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';

defineProps<{ modelValue: boolean }>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
  (e: 'createTransaction', data: any): void;
}>();

const formRef = ref<any>(null);

const formData = reactive({
  type: '' as 'credit' | 'debit' | '',
  amount: null as number | null,
  description: '',
});

const availableServices = [
  { id: 1, text: 'Manutenção Preventiva', price: 150.00, icon: 'mdi-wrench' },
  { id: 2, text: 'Limpeza Geral', price: 80.00, icon: 'mdi-broom' },
  { id: 3, text: 'Troca de Óleo', price: 220.00, icon: 'mdi-oil' },
  { id: 4, text: 'Alinhamento', price: 120.00, icon: 'mdi-car-settings' },
];

const selectedServices = ref<any[]>([]);

const transactionTypes = [
  { label: 'Crédito (Manual)', value: 'credit' },
  { label: 'Débito (Serviços)', value: 'debit' },
];

const toggleService = (service: any) => {
  const index = selectedServices.value.findIndex(s => s.id === service.id);
  if (index > -1) {
    selectedServices.value.splice(index, 1);
  } else {
    selectedServices.value.push(service);
  }
};

const isServiceSelected = (service: any) => {
  return selectedServices.value.some(s => s.id === service.id);
};

const calculatedTotal = computed(() => {
  return selectedServices.value.reduce((acc, curr) => acc + curr.price, 0);
});

const closeDialog = () => {
  emit('update:modelValue', false);
  formData.type = '';
  formData.amount = null;
  formData.description = '';
  selectedServices.value = [];
  formRef.value?.reset();
};

const submitForm = async () => {
  const { valid } = await formRef.value.validate();
  if (!valid) return;

  const payload = {
    type: formData.type,
    amount: formData.type === 'debit' ? calculatedTotal.value : formData.amount,
    description: formData.type === 'debit'
      ? `Serviços: ${selectedServices.value.map(s => s.text).join(', ')}`
      : formData.description,
  };

  emit('createTransaction', payload);
  closeDialog();
};

const amountRules = [(v: any) => !!v || 'Obrigatório', (v: number) => v > 0 || 'Inválido'];
const descriptionRules = [(v: string) => !!v || 'Obrigatório'];
</script>
