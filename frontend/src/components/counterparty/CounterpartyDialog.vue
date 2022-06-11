
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
  import { apiCreateCounterparty, apiUpdateCounterparty, Counterparty } from '../../api/counterparty';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  const emit = defineEmits(['onCancel', 'onSuccess', 'update:modelValue']);
  let dialogMode = 'new';

  const props = withDefaults(
    defineProps<{
      counterparty: Counterparty | null;
      modelValue: boolean;
      textHeader: string;
    }>(),
    {
      modelValue: false,
      textHeader: 'Редактирование предприятия',
      counterparty: null,
    },
  );

  let buttonSuccessTooltip = ref('Изменить');
  let buttonSuccessLabel = ref('Изменить');

  const counterpartyData = ref({})

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

      let out = {};
      for(let key in counterparty)
        out[key] = counterparty[key];

      counterpartyData.value = out;
      buttonSuccessTooltip.value = buttonSuccessLabel.value = counterparty.id ? 'Изменить' : 'Создать';
      dialogMode = counterparty.id ? 'update' : 'new';
    },
  );

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = async () => {
    dialog.value = false;

    if(dialogMode == 'update')
      await apiUpdateCounterparty(counterpartyData.value);
    else await apiCreateCounterparty(counterpartyData.value);
    emit('onSuccess', true);
  };
</script>

