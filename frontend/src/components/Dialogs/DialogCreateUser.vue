<template>
  <v-dialog width="600px" :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)">
    <v-card>
      <v-card-title class="text-blue-grey-darken-2 mt-1">Cadastrar Cliente</v-card-title>
      <v-divider class="mx-4" />

      <v-form ref="formRef" @submit.prevent="submitForm">
        <v-card-text class="mt-2">
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="formData.name"
                density="compact"
                label="Nome"
                variant="outlined"
                :rules="nameRules"
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="formData.email"
                density="compact"
                label="Email"
                variant="outlined"
                :rules="emailRules"
              />
            </v-col>
          </v-row>
        </v-card-text>

        <v-divider />

        <v-card-actions class="pa-4">
          <v-spacer />
          <v-btn @click="closeDialog" variant="text">Cancelar</v-btn>
          <v-btn type="submit" variant="tonal" color="primary">Salvar</v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue';

defineProps<{ modelValue: boolean }>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
  (e: 'createUser', data: { name: string; email: string }): void;
}>();

const formRef = ref<any>(null);

const formData = reactive({
  name: '',
  email: ''
});

const closeDialog = () => {
  emit('update:modelValue', false);
  formRef.value?.reset();
};

const submitForm = async () => {
  const { valid } = await formRef.value.validate();
  if (valid) {
    emit('createUser', { ...formData });
    closeDialog();
  }
};

const emailRules =[
  (value: string) => {
    if (value) return true
       return 'Campo obrigatório.'
     },
  (value: string) => (/.+@.+\..+/.test(value) ? true : 'E-mail deve ser válido.')
];

const nameRules =[
  (value: string) => {
    if (value) return true
       return 'Campo obrigatório.'
     },
  (value: string) => {
     if (value?.length <= 30) return true
       return 'O nome deve ter no máximo 10 caracteres.'
  },
];


</script>
