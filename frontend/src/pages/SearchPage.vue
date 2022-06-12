<template>
  <div class="q-pa-sm">
    <h3 class="non-selectable text-grey-9">Поиск компаний, продукции и услуг</h3>
    <div class="row q-pt-sm">
      <div class="col">
        <q-input
          v-model="search"
          :loading="loading"
          placeholder="Введите поисковой запрос"
          debounce="500"
        />
      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Hooks
   */
  import { useUserSearchPageGuard } from 'Src/hooks';

  useUserSearchPageGuard();

  const search = ref('');

  const loading = ref(false);

</script>
