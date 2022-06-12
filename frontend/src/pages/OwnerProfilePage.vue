<template>
  <q-page class="q-ma-md">
    <div class="row">
      <div class = "col-12 col-md-1"></div>
      <UserProfile class = "col-12 col-md-3"/>
      <CounterpartyCart  class = "col-12 col-md-7"/>
      <div class = "col-12 col-md-1"></div>
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
