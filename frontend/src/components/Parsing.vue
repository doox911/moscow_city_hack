<template>
  <h4 class="non-selected q-ma-none text-grey-9">
    {{ is_parsing ? 'Парсинг данных' : 'Запуск парсинга' }}
  </h4>
  <div class="row" style="display:grid; grid-template-columns: 1fr auto auto;">
    <div>
      <q-input
        v-model="search"
        :loading="loading"
        dense
        placeholder="Что ищем?"
      >
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>
    <div>
      <IconBtn
        :disabled="disabled || !search.length"
        :loading="is_parsing || loading"
        color="green"
        hover-color="green"
        icon="play_circle"
        text-tooltip="Запустить парсинг"
        @click="searching"
      />
    </div>
    <div>
      <IconBtn
        :disabled="disabled || !is_parsing"
        :loading="is_parsing || loading"
        color="red"
        hover-color="red"
        icon="stop_circle"
        text-tooltip="Остановить парсинг"
        @click="stopParsing"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { onMounted, ref, onBeforeUnmount } from 'vue';

  /**
   * Api
   */
  import {
    apiRunParsing,
    apiPingParsing,
    apiStopParsing,
  } from 'Src/api/parsing';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';

  const search = ref('');

  const disabled = ref(false);

  const is_parsing = ref(true);

  const loading = ref(true);

  let timeinterval_id: string | number | NodeJS.Timeout | undefined = undefined;

  function runPing() {
    clearTimeout(timeinterval_id);
    timeinterval_id = setTimeout(
      () => {
        apiPingParsing().then(r => {
          is_parsing.value = r;

          if (is_parsing.value) {
            runPing();
          }
        });
      },
      5000
    );
  }

  async function searching() {
    loading.value = true;

    await apiRunParsing(search.value);
    is_parsing.value = true;

    runPing();

    loading.value = false;

    search.value = '';
  }

  async function stopParsing() {
    loading.value = true;

    await apiStopParsing();

    loading.value = false;

    if (timeinterval_id) {
      clearInterval(timeinterval_id);
    }
  }

  onMounted(async () => {
    loading.value = true;

    is_parsing.value = await apiPingParsing();

    loading.value = false;

    if (is_parsing.value) {
      runPing();
    }
  });

  onBeforeUnmount(() => {
    if (timeinterval_id) {
      clearInterval(timeinterval_id);
    }
  });
</script>
