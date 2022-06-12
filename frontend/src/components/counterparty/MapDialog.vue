<template>
  <DialogCommonWrapper
    v-model="dialog"
    header-bg-color="primary"
    header-text="Карта"
    :open-dialog-button="false"
    @on-cancel="cancel"
  >
    <div ref="mapContainerRef"></div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  /**
   * Map
   */
  import YandexMap from '../../yandex-map/map';

  const emit = defineEmits(['update:modelValue', 'update:coordinate']);

  const props = withDefaults(
    defineProps<{
      coordinate: any;
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

  const mapContainerRef = ref<any>(null);

  watch(mapContainerRef, () => {
    const mapContainer: HTMLElement = mapContainerRef.value;

    if (dialog.value && props.coordinate) {
      mapContainer.appendChild(
        YandexMap.show(
          props.coordinate.lat,
          props.coordinate.lon,
          props.coordinate.title,
        ),
      );
    } else YandexMap.hide();
  });

  const cancel = () => {
    dialog.value = false;
  };
</script>
