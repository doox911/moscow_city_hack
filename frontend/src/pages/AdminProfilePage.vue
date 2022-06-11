<template>
  <q-page>
    <div class="q-pa-md">
      <div class="row justify-center">
        <div class="col-12 col-md-1"></div>
        <div class="col-12 col-md-2" style="margin-bottom: 10px;">
          <q-btn flat color="primary" label="Мой профиль" />
          <q-btn flat color="primary" label="Заявки" />
          <q-btn flat color="primary" label="Настройки профиля" />
          <q-btn flat color="primary" label="Добавление организации" />
        </div>
        <div class="col-12 col-md-7">
          <q-form
            @submit="onSubmit"
            class="q-gutter-md">
          </q-form>
          <div>Append new task! Common!</div>
        </div>
        <div class="col-12 col-md-1"></div>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
  import { onMounted } from 'vue';

  /**
   * Routers
   */
  import { useRoute, useRouter } from 'vue-router';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from '../stores/userStore';

  const route = useRoute();

  const router = useRouter();

  const { user } = storeToRefs(userStore());

  async function onSubmit()
  {

  }
  onMounted(() => {
    if (route.name !== user.value.role + 'Profile') {
      router.push({
        name: user.value.role + 'Profile',
      })
    }
  });
</script>
