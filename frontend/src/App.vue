<template>
  <v-app>
    <span v-if="isLoading">
      <v-progress-linear
       color="cyan"
       indeterminate
       ></v-progress-linear>
    </span>
   <router-view />

      <v-snackbar
      v-model="show"
      :color="color"
      elevation="34"
      :timeout="8000"
      location="top end"
    >
    <span>
      {{ message }}
    </span>
    <template v-slot:actions>
      <v-btn variant="text" @click="show = false">Fechar</v-btn>
    </template>
  </v-snackbar>

     <!-- Overlay Global de Loading -->
    <v-overlay
      v-model="globalLoading"
      class="align-center justify-center"
      persistent
    >
      <v-progress-circular
        color="primary"
        indeterminate
        size="58"
      ></v-progress-circular>
    </v-overlay>



  </v-app>
</template>

<script lang="ts" setup>
import { useToastStore } from '@/stores/toastStore'
import { useLoadingStore } from '@/stores/loadingStore'
import { storeToRefs } from 'pinia';

const toastStore = useToastStore();
const loadingStore = useLoadingStore();
const { show, message, color } = storeToRefs(toastStore);
const { isLoading, globalLoading } = storeToRefs(loadingStore);

</script>
