<template>
  <q-page class="q-mx-md">
      <Parsing />
  </q-page>
</template>

<script setup lang="ts">
  /**
   * Components
   */
  import Parsing from 'Components/Parsing.vue';

  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';

  useUserPageGuard();
</script>
