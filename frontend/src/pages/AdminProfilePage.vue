<template>
  <q-page class="q-ma-md">
    <div class="row">
      <div class = "col-12 col-md-3">
        <UserProfile />
      </div>
    </div>
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
