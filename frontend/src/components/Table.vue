<template>
  <v-data-table
    :show-select="false"
    :headers="headers"
    :items="items"
    :search="search"
    :loading="loading"
    density="compact"
    hover
  >

  <template #loading>
      <v-skeleton-loader type="table-row-divider@5"></v-skeleton-loader>
  </template>

  <template #no-data>
    <div class="text-center py-4">
    <v-icon icon="mdi-database-off" class="mr-2" />
      Nenhum dado disponível
    </div>
  </template>

  <template #[`item.created_at`]="{ item }">
      {{ formatDate(item.created_at) }}
    </template>

    <template #[`item.type`]="{ item }">
    <v-chip
      class="ma-2"
      :color="item.type === 'credit' ? 'success' : 'error'"
      variant="flat"
      size="small"
    >
      <v-icon
        :icon="item.type === 'credit' ? 'mdi-plus-circle' : 'mdi-minus-circle'"
        start
      ></v-icon>
      {{ item.type === 'credit' ? 'Crédito' : 'Débito' }}
    </v-chip>
  </template>
    <template #[`item.actions`]="{ item }">
      <div class="text-end">
        <v-menu>
          <template #activator="{ props }">
            <v-btn
              icon="mdi-dots-vertical"
              variant="text"
              size="small"
              v-bind="props"
            ></v-btn>
          </template>

          <v-list density="compact">
            <v-list-item
              @click="handleAction('selectItem', item)"
              prepend-icon="mdi-magnify"
              title="Visualizar"
            />

          </v-list>
        </v-menu>
      </div>
    </template>

    <template #top>
      <v-text-field
        v-model="search"
        label="Buscar"
        density="compact"
        class="pb-5"
        prepend-inner-icon="mdi-magnify"
        single-line
        hide-details
      ></v-text-field>
    </template>

  </v-data-table>
</template>

<script lang="ts" setup>
import { ref  } from 'vue';
import { formatDate } from '@/Utils/formatters';

defineProps<{
  headers: Array<any>;
  items: Array<any>;
  loading: boolean
}>();

const emit = defineEmits(['selectItem']);
const search = ref('');

const handleAction = (actionCode: string, item: any) => {
  if (actionCode === 'selectItem') {
         emit('selectItem', item);
  }
};
</script>
