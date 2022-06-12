<template>
  <q-select
    v-model="selected"
    :loading="loading"
    :options="options"
    use-input
    input-debounce="0"
    label="Выбрать ОКВЭД"
    option-label="name"
    @filter="filterFn"
  />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia';
  import { okvedStore } from 'Stores/okvedStore';

  /**
   * Types
   */
  import type { OKVED } from 'Src/api';

  const emit = defineEmits(['update:modelValue']);

  const props = withDefaults(
    defineProps<{
      modelValue?: OKVED;
    }>(),
    {},
  );

  const { okved } = storeToRefs(okvedStore());

  const options = ref([...okved.value]);

  watch(okved, (v: OKVED[]) => {
    options.value = v;
  });

  const loading = ref(false);

  const selected = computed({
    get() {
      return props.modelValue;
    },
    set(v?: OKVED) {
      emit('update:modelValue', v);
    },
  });

  function filterFn(val: string, update: (arg: () => void) => void) {
    if (val === '') {
      update(() => {
        options.value = okved.value;
      });
      return;
    }

    update(() => {
      const needle = val.toLowerCase();

      options.value = okved.value.filter(
        (v) => v.name.toLowerCase().indexOf(needle) > -1,
      );
    });
  }
</script>
