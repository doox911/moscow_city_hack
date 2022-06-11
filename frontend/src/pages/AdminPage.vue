<template>
  <q-page padding>
    Admin page
  </q-page>
</template>

<script setup lang="ts">
  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';

  useUserPageGuard();
</script>
