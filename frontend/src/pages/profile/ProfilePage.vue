<template>
  <q-page padding>
    Profile page

    <h4>{{ user.name }}</h4>
    <h4>{{ user.second_name }}</h4>
    <hr>
    <div>
        <div>Роль: {{ user.role }}</div>
        <div>email: {{ user.email }}</div>
        <div>последняя активность: {{ user.updated_at }}</div>
        <div>создан: {{ user.created_at }}</div>
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
  import { userStore } from '../../stores/userStore';

  const route = useRoute();

  const router = useRouter();

  const { user } = storeToRefs(userStore());

</script>
