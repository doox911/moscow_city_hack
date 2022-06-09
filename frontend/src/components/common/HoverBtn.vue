<template>
  <q-btn
    :class="classes"
    :color="computed_color"
    :disable="disabled"
    :type="type"
    outline
    @mouseover="setHovering"
    @mouseout="unsetHovering"
  >
    {{ label }}
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
      fullWidth?: boolean;
      hoverColor?: string;
      label?: string;
      mSm?: boolean;
      tooltipText?: string;
      type?: 'submit' | 'reset' | 'button';
    }>(),
    {
      color: 'primary',
      disabled: false,
      fullWidth: false,
      hoverColor: 'primary',
      label: '',
      mSm: false,
      tooltipText: '',
      type: 'button',
    },
  );

  const { is_hover, setHovering, unsetHovering } = useHover();

  const computed_color = computed<string>(() => {
    return is_hover.value ? props.hoverColor : props.color;
  });

  const tooltip = ref(false);

  const classes = computed(() => {
    return {
      'full-width': props.fullWidth,
      'q-mb-sm': props.mSm,
    };
  });

  watch(
    () => props.disabled,
    (v: boolean) => {
      if (!v) {
        tooltip.value = v;
      }
    },
  );
</script>
