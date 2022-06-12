<template>
  <DialogCommonWrapper
    v-model="dialog"
    button-success-label="Сохранить"
    :button-text="buttonText"
    :button-success-tooltip="buttonSuccessTooltip"
    :header-bg-color="headerColor"
    :header-text="textHeader"
    :loading-open-dialog="loading"
    :reset-button="useReset"
    @on-cancel="cancel"
    @on-reset="onReset"
    @on-success="success"
  >
    <div style="width: 100%">
      <q-input
        v-model="u.second_name"
        :rules="[requiredStringRule]"
        label="Фамилия"
      />
    </div>
    <div style="width: 100%">
      <q-input v-model="u.name" :rules="[requiredStringRule]" label="Имя" />
    </div>
    <div style="width: 100%">
      <q-input v-model="u.patronymic" label="Отчество" />
    </div>
    <div style="width: 100%">
      <q-input
        v-model="u.email"
        :rules="[requiredStringRule]"
        autocomplete="false"
        label="Email"
      />
    </div>
    <div style="width: 100%">
      <q-select
        v-if="isAdmin"
        :model-value="{ value: u.role, label: RolesDescription[u.role] }"
        :options="roleList"
        :rules="[requiredSelectRule]"
        label="Роль"
        option-label="label"
        @update:model-value="
          (e) => {
            u.role = e.value;
          }
        "
      />
    </div>
    <div style="width: 100%">
      <q-input
        v-model="u.password"
        :type="isPwd ? 'password' : 'text'"
        autocomplete="false"
        label="Пароль"
      >
        <template v-slot:append>
          <q-icon
            :name="isPwd ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="isPwd = !isPwd"
          />
        </template>
      </q-input>
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';

  /**
   * Api
   */
  import { apiSignupUser, apiUpdateUserInfo } from 'Src/api/users';

  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';

  /**
   * Constants
   */
  import { Roles, RolesDescription } from 'Src/constants';

  /**
   * Rules
   */
  import { requiredStringRule, requiredSelectRule } from 'Src/common/rules';

  /**
   * Store
   */
  import { userStore } from 'Src/stores';
  import { storeToRefs } from 'pinia';

  /**
   * Types
   */
  import type { User } from 'Src/stores';

  const { user } = storeToRefs(userStore());

  const isAdmin = computed(() => user.value.role == Roles.Admin);

  const roleList = [
    { value: Roles.Admin, label: RolesDescription[Roles.Admin] },
    { value: Roles.Government, label: RolesDescription[Roles.Government] },
    { value: Roles.Owner, label: RolesDescription[Roles.Owner] },
    { value: Roles.Guest, label: RolesDescription[Roles.Guest] },
  ];

  const emit = defineEmits([
    'onCancel',
    'onSuccess',
    'update:modelValue',
    'update:user',
    'onReset',
  ]);

  const props = withDefaults(
    defineProps<{
      user: User;
      modelValue?: boolean;
      useReset?: boolean;
      loading?: boolean;
    }>(),
    {
      modelValue: false,
      useReset: true,
      loading: false,
    },
  );

  const buttonText = computed(() => {
    return user.value.id === null || user.value.id < 0
      ? 'Создать пользователя'
      : 'Редактировать пользователя';
  });

  const headerColor = computed(() => {
    return user.value.id === null || user.value.id < 0 ? 'primary' : 'warning';
  });

  const textHeader = computed(() => {
    return user.value.id === null || user.value.id < 0
      ? 'Создать пользователя'
      : 'Изменить пользователя';
  });

  const buttonSuccessTooltip = computed(() => {
    return user.value.id === null || user.value.id < 0
      ? 'Создать пользователя'
      : 'Изменить пользователя';
  });

  const isPwd = ref(true);

  const dialog = computed({
    get() {
      return props.modelValue;
    },
    set(value: boolean) {
      emit('update:modelValue', value);
    },
  });

  const u = computed({
    get() {
      return props.user;
    },
    set(v: User) {
      emit('update:user', v);
    },
  });

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = async () => {
    dialog.value = false;

    if (u.value.id === null || u.value.id < 0) {
      await apiSignupUser(u.value);
    } else {
      await apiUpdateUserInfo(u.value);
    }

    emit('onSuccess', true);
  };

  function onReset() {
    emit('onReset', true);
  }
</script>
