<template>
  <q-page class="q-mx-md">
    <div class="row">
      <h3>Компания</h3>
    </div>
    
    <q-separator />

    <div class="row">
      <h3>Продукция</h3>
    </div>

    <q-separator />


  </q-page>
</template>

<script setup lang="ts">
  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';

  useUserPageGuard();
</script>
