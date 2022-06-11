<template>
  <q-page>
    Личная страница компании(й)
  </q-page>
</template>

<script setup lang="ts">
  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  useUserProfilePageGuard();
</script>
