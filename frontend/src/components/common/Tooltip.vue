<template>
  <q-tooltip v-model="tooltip">
    <span class="text-body2" v-text="text" />
  </q-tooltip>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  const emit = defineEmits(['update:modelValue']);

  const props = withDefaults(
    defineProps<{
      modelValue?: boolean;
      text?: string;
    }>(),
    {
      modelValue: false,
      text: '',
    },
  );

  const tooltip = computed({
    get() {
      return props.modelValue;
    },
    set(value: boolean) {
      emit('update:modelValue', value);
    },
  });
</script>
