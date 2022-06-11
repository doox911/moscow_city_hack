<template>
  <q-btn
    :color="computed_color"
    :icon="icon"
    :disable="disabled"
    flat
    round
    @mouseover="setHovering"
    @mouseout="unsetHovering"
  >
    <Tooltip v-if="tooltipText?.length" v-model="tooltip" :text="tooltipText" />
  </q-btn>
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Components
   */
  import Tooltip from 'Components/common/Tooltip.vue';

  /**
   * Hooks
   */
  import { useHover } from 'src/hooks';

  const props = withDefaults(
    defineProps<{
      color?: string;
      disabled?: boolean;
      hoverColor?: string;
      icon?: string;
      tooltipText?: string;
    }>(),
    {
      color: 'primary',
      disabled: false,
      hoverColor: 'primary',
      icon: 'insert_emoticon',
      tooltipText: '',
    },
  );

  const { is_hover, setHovering, unsetHovering } = useHover();

  const computed_color = computed<string>(() => {
    return is_hover.value ? props.color : props.hoverColor;
  });

  const tooltip = ref(false);

  watch(
    () => props.disabled,
    (v: boolean) => {
      if (!v) {
        tooltip.value = v;
      }
    },
  );
</script>