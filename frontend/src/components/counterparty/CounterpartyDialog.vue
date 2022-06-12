
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
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.name"
        label="Название"
        />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.full_name"
        label="Полное название"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.inn"
        label="ИНН"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.ogrn"
        label="ОГРН"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.adress"
        label="Адрес"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.email"
        label="Почта"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.phone"
        label="Телефон"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.site"
        label="Сайт"
      />
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  /**
   * Api
   */
  import { apiCreateCounterparty, apiUpdateCounterparty, Counterparty } from 'Src/api/counterparty';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  const emit = defineEmits([
    'onCancel',
    'onSuccess',
    'update:modelValue',
    'update:Counterparty',
  ]);

  const props = withDefaults(
    defineProps<{
      counterparty: Counterparty;
      modelValue?: boolean;
    }>(),
    {
      modelValue: false,
    }
  );

  const dialog = computed({
    get() {
      return props.modelValue;
    },
    set(value: boolean) {
      emit('update:modelValue', value);
    },
  });

  const counterpartyData = computed({
    get() {
      return props.counterparty;
    },
    set(value?: Counterparty) {
      emit('update:Counterparty', value);
    },
  });

  const headerColor = computed(() => {
    return counterpartyData.value.id
      ? 'warning'
      : 'primary';
  });

  const textHeader = computed(() => {
    return counterpartyData.value.id
      ? 'Изменить предприятие'
      : 'Создать предприятие';
  });
  const buttonSuccessTooltip = computed(() => {
    return counterpartyData.value.id
      ? 'Изменить предприятие'
      : 'Создать предприятие';
  });

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = async () => {
    dialog.value = false;

    counterpartyData.value.id
      ? await apiUpdateCounterparty(counterpartyData.value)
      : await apiCreateCounterparty(counterpartyData.value);
    
    emit('onSuccess', true);
  };
</script>

