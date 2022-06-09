<template>
  <div class="q-pa-md">
    <q-btn
      v-if="openDialogButton"
      :disable="disabled"
      :label="buttonText"
      :color="buttonColor"
      @click="onOpen"
    >
      <Tooltip
        v-if="buttonTooltip?.length"
        v-model="tooltip"
        :text="buttonTooltip"
      />
    </q-btn>
    <q-dialog v-model="dialog" :persistent="persistent" :maximized="maximized">
      <q-card :style="style">
        <q-form @submit="onSuccess" @reset="onReset">
          <q-card-section :class="header_classes">
            <div class="non-selectable text-h6" v-text="headerText" />
            <q-space />
            <IconBtn
              color="white"
              hover-color="white"
              icon="close"
              tooltip="Закрыть"
              @click="onClose"
            />
          </q-card-section>
          <q-separator />
          <q-card-section :class="content_classes">
            <slot />
          </q-card-section>
          <q-separator />
          <slot name="actions">
            <q-card-actions
              v-if="cancelButton || resetButton || successButton"
              class="q-pa-md"
            >
              <div ref="actions" class="full-width q-gutter-sm row justify-end">
                <slot name="action-cancel">
                  <div v-if="cancelButton" class="col-xs-12 col-sm-3">
                    <HoverBtn
                      :full-width="true"
                      :hover-color="buttonCancelHoverColor"
                      :label="buttonCancelLabel"
                      :tooltip-text="buttonCancelTooltip"
                      @click="onCancel"
                    />
                  </div>
                </slot>
                <slot name="action-reset">
                  <div v-if="resetButton" class="col-xs-12 col-sm-3">
                    <HoverBtn
                      :full-width="true"
                      :hover-color="buttonResetHoverColor"
                      label="Сбросить"
                      tooltip-text="Сбросить значения формы"
                      type="reset"
                    />
                  </div>
                </slot>
                <slot name="action-success">
                  <div v-if="successButton" class="col-xs-12 col-sm-3">
                    <HoverBtn
                      :full-width="true"
                      :hover-color="buttonSuccessHoverColor"
                      :label="buttonSuccessLabel"
                      :tooltip-text="buttonSuccessTooltip"
                      type="submit"
                    />
                  </div>
                </slot>
              </div>
            </q-card-actions>
          </slot>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';

  /**
   * Components
   */
  import HoverBtn from 'Components/common/HoverBtn.vue';
  import IconBtn from 'Components/common/IconBtn.vue';
  import Tooltip from 'Components/common/Tooltip.vue';

  /**
   * Constants
   */
  import { dialogEmits, dialogProps } from 'Components/common/dialogs';

  const emit = defineEmits(dialogEmits);

  /**
   * @TODO Баг в Vue. Переделать на DialogProps, когда исправят
   * @link https://github.com/vuejs/core/issues/4294
   */
  const props = withDefaults(
    defineProps<{
      buttonCancelHoverColor?: string;
      buttonCancelLabel?: string;
      buttonCancelTooltip?: string;
      buttonColor?: string;
      buttonResetHoverColor?: string;
      buttonSuccessHoverColor?: string;
      buttonSuccessLabel?: string;
      buttonSuccessTooltip?: string;
      buttonText?: string;
      buttonTooltip?: string;
      cancelButton?: boolean;
      contentWrapperClasses?: string;
      disabled?: boolean;
      headerBgColor?: string;
      headerText?: string;
      headerTextColor?: string;
      loading?: boolean;
      maximized?: boolean;
      maxWidth?: number;
      modelValue: boolean;
      openDialogButton?: boolean;
      persistent?: boolean;
      resetButton?: boolean;
      successButton?: boolean;
    }>(),
    { ...dialogProps },
  );

  const dialog = computed({
    get() {
      return props.modelValue;
    },
    set(v: boolean) {
      emit('update:modelValue', v);
    },
  });

  const actions = ref<Element | null>(null);

  const tooltip = ref(false);

  const cc = props.contentWrapperClasses?.length
    ? props.contentWrapperClasses.split(' ').map((c) => ({ [c]: true }))
    : [];

  const content_classes = {
    ...cc,
    row: true,
  };

  const header_classes = {
    row: true,
    'items-center': true,
    [`bg-${props.headerBgColor}`]: true,
    [`text-${props.headerTextColor || 'white'}`]: true,
  };

  const style = props.maximized
    ? ''
    : ['width: 100%;', `max-width: ${props.maxWidth}px;`].join(' ');

  const onCancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const onClose = () => {
    dialog.value = false;

    emit('onClose');
  };

  const onOpen = () => {
    dialog.value = true;

    emit('onOpen');
  };

  const onReset = () => {
    emit('onReset');
  };

  const onSuccess = () => {
    dialog.value = false;

    emit('onSuccess');
  };
</script>
