<template>
  <v-app>
    <SideNavBar v-model="drawerIsOpen"/>
    <Header @toggle-drawer="drawerIsOpen = !drawerIsOpen"/>

    <v-main class="bg-grey-lighten-5">
    <router-view />
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import SideNavBar from '@/components/SideNavBar.vue';
import Header from '@/components/Header.vue';

const drawerIsOpen = ref(false);

</script>



