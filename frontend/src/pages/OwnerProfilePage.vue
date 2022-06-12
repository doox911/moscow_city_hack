<template>
  <q-page class="q-ma-md">
    <div class="row">
      <div class = "col-12 col-md-4" style = "margin-bottom: 10px;">
        <UserProfile/>
      </div>
      <div class = "col-12 col-md-8">
        <CounterpartyCart/>
      </div>
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
