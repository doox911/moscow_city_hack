<template>
  <DialogCommonWrapper
    v-model="dialog"
    :button-success-tooltip="buttonSuccessTooltip"
    :header-text="textHeader"
    :open-dialog-button="false"
    :reset-button="false"
    header-bg-color="warning"
    @on-cancel="cancel"
    @on-success="success"
  >
    <slot />
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  const emit = defineEmits(['onCancel', 'onSuccess', 'update:modelValue']);

  const props = withDefaults(
    defineProps<{
      buttonSuccessTooltip?: string;
      modelValue: boolean;
      textHeader?: string;
    }>(),
    {
      modelValue: false,
      textHeader: 'Подтверждение',
      buttonSuccessTooltip: 'Подтвердить',
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

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = () => {
    dialog.value = false;

    emit('onSuccess', true);
  };
</script>
