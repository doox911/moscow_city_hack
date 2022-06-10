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

        <template v-if = "isLoggedIn()">
          <q-btn flat dense label="Main" to = "/main"/>
          <q-btn flat dense label="Logout" @click = "logout()"/>
        </template>

        <template v-else>
          <q-btn flat dense label="Registration" to = "/registration"/>
          <q-btn flat dense label="Login" to = "/login"/>
        </template>
        {{ user?.name }}
        <div style = "margin-left: auto;">Task #3</div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
  import { useRouter } from 'vue-router';
  import { userStore } from '../stores/userStore';
  import AuthService from '../services/auth.service';

  const router = useRouter();
  const { user } = userStore();
  
  function isLoggedIn(): boolean {
    return AuthService.isAuthenticated
  }
  async function logout() {
    await AuthService.logout();
    router.push("/login");
  }
</script>
