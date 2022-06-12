<template>
  <div class="q-pa-sm">
    <h3 class="q-my-sm non-selectable text-grey-9">Поиск компаний, продукции и услуг</h3>
    <p class="non-selectable text-weight-regular text-h5 text-grey-9"> Фильтры: </p>
    <div class="row">
      <div class="col-auto">
        <SelectOkved v-model="selected_okved"/>
      </div>
    </div>
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
   * Components
   */
  import SelectOkved from 'Components/SelectOkved.vue';

  /**
   * Hooks
   */
  import { useUserSearchPageGuard } from 'Src/hooks';

  /**
   * Types
   */
  import type { OKVED } from 'Src/api';

  useUserSearchPageGuard();

  const search = ref('');

  const loading = ref(false);

  const selected_okved = ref({
    code: '',
    name: '',
    additional_info: '',
  });

</script>
