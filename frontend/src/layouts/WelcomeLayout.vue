<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
  import { storeToRefs } from 'pinia'

  /**
   * Routers
   */
  import { useRouter } from 'vue-router';

  /**
   * Store
   */
  import { userStore } from '../stores/userStore';

  /**
   * Services
   */
  import AuthService from '../services/auth.service';

  const router = useRouter();
  
  const { user } = storeToRefs(userStore());

  async function logout() {
    await AuthService.logout();

    router.push('/login');
  }
</script>
