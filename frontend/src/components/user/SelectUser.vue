<template>
  <q-select
    v-model="selected"
    :loading="loading"
    :options="allUser"
    label="Выбрать пользователя"
    option-label="name"
  />
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia';
  import { User, userStore } from '../../stores';

  const emit = defineEmits(['update:modelValue']);

  const props = withDefaults(
    defineProps<{
      modelValue?: User;
    }>(),
    {},
  );

  const loading = ref(false);

  const { allUser } = storeToRefs(userStore());

  const selected = computed({
    get() {
      return props.modelValue;
    },
    set(v?: User) {
      emit('update:modelValue', v);
    },
  });
</script>
