<template>
  <DialogCommonWrapper
    v-model="dialog"
    button-success-label="Сохранить"
    :button-success-tooltip="buttonSuccessTooltip"
    :header-bg-color="headerColor"
    :header-text="textHeader"
    :open-dialog-button="false"
    @on-cancel="cancel"
    @on-success="success"
  >
    <div style="width: 100%">
      <q-input v-model="goodData.name" label="Название" />
    </div>
    <div style="width: 100%">
      <q-input v-model="goodData.brand" label="Брэнд" />
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  /**
   * Api
   */
  import { apiCreateGood, apiUpdateGood, Good } from 'Src/api/good';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  const emit = defineEmits([
    'onCancel',
    'onSuccess',
    'update:modelValue',
    'update:Good',
  ]);

  const props = withDefaults(
    defineProps<{
      good: Good;
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

  const goodData = computed({
    get() {
      return props.good;
    },
    set(value?: Good) {
      emit('update:Good', value);
    },
  });

  const headerColor = computed(() => {
    return goodData.value.id ? 'warning' : 'primary';
  });

  const textHeader = computed(() => {
    return goodData.value.id ? 'Изменить товар' : 'Создать товар';
  });
  const buttonSuccessTooltip = computed(() => {
    return goodData.value.id ? 'Изменить товар' : 'Создать товар';
  });

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = async () => {
    dialog.value = false;

    goodData.value.id
      ? await apiUpdateGood(goodData.value)
      : await apiCreateGood(goodData.value);

    emit('onSuccess', true);
  };
</script>
