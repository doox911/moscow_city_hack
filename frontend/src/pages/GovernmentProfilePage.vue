<template>
  <q-page class="q-ma-md">
    <UserProfile />
  </q-page>
</template>

<script setup lang="ts">

  /**
   * Components
   */
  import UserProfile from 'Components/user/UserProfile.vue'
  
  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  useUserProfilePageGuard();
</script>
