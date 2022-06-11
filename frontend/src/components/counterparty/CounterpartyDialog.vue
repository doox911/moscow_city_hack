
<template>
  <DialogCommonWrapper
    v-model="dialog"
    :button-success-tooltip="buttonSuccessTooltip"
    :button-success-label="buttonSuccessLabel"
    :header-text="textHeader"
    :open-dialog-button="false"
    :reset-button="false"
    header-bg-color="warning"
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
        label="телефон"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="counterpartyData.site"
        label="сайт"
      />
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';
  import { Counterparty } from '../../api/counterparty';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  const emit = defineEmits(['onCancel', 'onSuccess', 'update:modelValue']);

  const props = withDefaults(
    defineProps<{
      counterparty: Counterparty | null;
      modelValue: boolean;
      textHeader: string;
      buttonSuccessTooltip: string;
      buttonSuccessLabel: string;
    }>(),
    {
      modelValue: false,
      textHeader: 'Редактирование предприятия',
      counterparty: null,
      buttonSuccessTooltip: 'Изменить',
      buttonSuccessLabel: 'Изменить'
    },
  );

  const counterpartyData = ref({
    id: null,
    user_id: null,
    name: '',
    full_name: '',
    inn: '',
    ogrn: '',
    adress: '',
    email: '',
    phone: '',
    site: '',
  })

  const dialog = computed({
    get() {
      return props.modelValue;
    },
    set(value: boolean) {
      emit('update:modelValue', value);
    },
  });

  watch(
    () => props.counterparty,
    (counterparty: Counterparty | null) => {
      counterpartyData.value = counterparty;
    },
  );

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = () => {
    dialog.value = false;

    emit('onSuccess', true);
  };
</script>

