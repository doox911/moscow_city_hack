<template>
  <q-page class="q-ma-md">
    <UserProfile />
    <CounterpartyCart />
  </q-page>
</template>

<script setup lang="ts">

  /**
   * Components
   */
  import UserProfile from 'Components/user/UserProfile.vue'
  import CounterpartyCart from 'Components/counterparty/CounterpartyCart.vue'

  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  useUserProfilePageGuard();
</script>
