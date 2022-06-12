<template>
  <div class="row">
    <div class="cols-auto">
      <q-input v-model="search" dense placeholder="Что ищем?">
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>
    <div class="cols-auto">
      <IconBtn
        :disabled="disabled"
        color="green"
        hover-color="green"
        icon="play"
        text-tooltip="Запустить парсинг"
        @click="searching"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';

  const search = ref('');

  const disabled = ref(false);

  let timeuot_id: number | null = null;

  // async function searching() {}
</script>
