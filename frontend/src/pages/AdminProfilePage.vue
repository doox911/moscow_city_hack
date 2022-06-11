<template>
  <q-page>
    <div class='q-pa-md'>
      <div class='row justify-center'>
        <div class='col-12 col-md-2'></div>
        <div class='col-12 col-md-8'>
          <q-page-container>
            <router-view />
          </q-page-container>
        </div>
        <div class='col-12 col-md-2'></div>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">

  /**
   * Routers
   */
  import { useRoute, useRouter } from 'vue-router';
  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  useUserProfilePageGuard();

</script>
