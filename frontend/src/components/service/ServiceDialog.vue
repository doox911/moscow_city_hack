<template>
  <DialogCommonWrapper
    v-model="dialog"
    :header-bg-color="headerColor"
    header-text="Просмотр услуги"
    :open-dialog-button="false"
    @on-cancel="cancel"
  >
    <div style="width: 100%">
      {{ serviceData.name }}
    </div>
    <div style="width: 100%; margin-top: 10px">
      {{ serviceData.additional_info }}
    </div>
    <div style="width: 100%">
      {{ serviceData.code }}
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  /**
   * Api
   */
  import { Service } from 'Src/api/service';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  const emit = defineEmits(['update:modelValue', 'update:service', 'onCancel']);

  const props = withDefaults(
    defineProps<{
      service: Service;
      modelValue?: boolean;
    }>(),
    {
      modelValue: false,
    },
  );

  const dialog = computed({
    get() {
      return props.modelValue;
    },
    set(value: boolean) {
      emit('update:modelValue', value);
    },
  });

  const serviceData = computed({
    get() {
      return props.service;
    },
    set(value?: Service) {
      emit('update:service', value);
    },
  });

  const headerColor = computed(() => {
    return serviceData.value.id ? 'warning' : 'primary';
  });

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };
</script>
