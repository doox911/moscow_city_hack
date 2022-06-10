<template>
  <router-view />
</template>

<script lang="ts">
  import { defineComponent } from 'vue';
  import ApiRequest from './api/ApiRequest';
  import AuthService from './services/auth.service';

  export default defineComponent({
    name: 'App',
    setup()
    {
    }
  });
</script>
