<template>
  <q-page>
    <div class='q-pa-md'>
      <div class='row justify-center'>
        <div class='col-12 col-md-2'>
        <div class='col-12 col-md-8'>
          <div>{{ getUserInfo }}</div>
          <hr>
          <div>
              <div>Роль: {{ user.role }}</div>
              <div>email: {{ user.email }}</div>
              <div>последняя активность: {{ user.updated_at }}</div>
              <div>создан: {{ user.created_at }}</div>
          </div>
          </div>
        </div>
        <div class='col-12 col-md-2'></div>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">

  /**
   * Routers
   */
  import { useRoute, useRouter } from 'vue-router';
  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from '../stores/userStore';

  /**
   * Common
   */
  import { getUserName } from 'Src/common';
  import { computed } from 'vue';

  useUserProfilePageGuard();

  const { user } = storeToRefs(userStore());
  const getUserInfo = computed(() => getUserName(user.value));

</script>
