<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
        />

        <template v-if = "isLoggedIn">
          <q-btn flat dense label="Main" to = "/main"/>
          <q-btn flat dense label="Logout" @click = "logout()"/>
        </template>

        <template v-else>
          <q-btn flat dense label="Registration" to = "/registration"/>
          <q-btn flat dense label="Login" to = "/login"/>
        </template>

        <div style = "margin-left: auto;">Task #3</div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
  import { defineComponent, ref } from 'vue';
  import AuthService from '../services/auth.service';

  export default defineComponent({
    name: 'MainLayout',

    components: {
    },

    setup () {
      return {
      }
    },
    computed: {
      isLoggedIn: function() {
        return AuthService.isAuthenticated
      }
    },
    methods: {
      async logout () {
        await AuthService.logout();
        this.$router.push("/login");
      }
    },
  });
</script>
