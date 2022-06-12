<template>
  <q-page class="q-ma-md">
    <div class="row">
      <UserProfile class = "col-12 col-md-4"/>
      <CounterpartyCart  class = "col-12 col-md-8"/>
    </div>
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
