<template>
  Личная страница госслужащего
  <q-page-container>
    <router-view />
  </q-page-container>
</template>

<script setup lang="ts">
  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  useUserProfilePageGuard();
</script>
