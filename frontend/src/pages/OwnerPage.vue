<template>Owner page</template>

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

  onMounted(() => {
    if (route.name !== user.value.role) {
      router.push({
        name: user.value.role,
      })
    }
  });
</script>
