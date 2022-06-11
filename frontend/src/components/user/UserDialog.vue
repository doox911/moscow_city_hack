
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
        v-model="userData.secondName"
        :rules="[ requiredStringRule ]"
        label="Фамилия"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="userData.name"
        :rules="[ requiredStringRule ]"
        label="Имя"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="userData.patronymic"
        label="Отчество"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="userData.email"
        :rules="[ requiredStringRule ]"
        label="Email"
      />
    </div>
    <div style = "width: 100%">
      <q-select
        v-if = "isAdmin"
        v-model="userData.role"
        :options="roleList"
        option-label="label"
        :rules="[ requiredSelectRule ]"
        label="Роль"
      />
    </div>
    <div style = "width: 100%">
      <q-select
        v-if = "userData.role?.value == Roles.Owner"
        v-model="userData.owner"
        :rules="[ (e) => e !== undefined ]"
        label="Предприятие"
      />
    </div>
    <div style = "width: 100%">
      <q-input
        v-model="userData.password"
        :rules="[ requiredStringRule ]"
        :type="isPwd ? 'password' : 'text'"
        label="Пароль"
        ref="fldPasswordChange"
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
  import { computed, ref, watch } from 'vue';
  /**
   * Components
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue';
  /**
   * Rules
   */
  import { requiredStringRule, requiredSelectRule } from 'Src/common/rules';
  import { User } from '../../stores';
  import { apiSignupUser, apiUpdateUserInfo } from '../../api/users';
  import { Roles, RolesDescription } from '../../constants';
  import { userStore } from 'Src/stores';
  import { storeToRefs } from 'pinia';

  const { user } = storeToRefs(userStore());
  const isAdmin = ref(user.value.role == Roles.Admin);

  const roleList = ref([
    { value: Roles.Admin, label: RolesDescription[Roles.Admin] },
    { value: Roles.Government, label: RolesDescription[Roles.Government] },
    { value: Roles.Owner, label: RolesDescription[Roles.Owner] },
    { value: Roles.Guest, label: RolesDescription[Roles.Guest] },
  ])
  const emit = defineEmits(['onCancel', 'onSuccess', 'update:modelValue']);
  let dialogMode = 'new';

  const props = withDefaults(
    defineProps<{
      user: User | null;
      modelValue: boolean;
      textHeader: string;
    }>(),
    {
      user: null,
      modelValue: false,
      textHeader: 'Редактирование пользователя',
    },
  );

  let buttonSuccessTooltip = ref('Изменить');
  let buttonSuccessLabel = ref('Изменить');

  const userData = ref({})
  let isPwd = ref(true);

  const dialog = computed({
    get() {
      return props.modelValue;
    },
    set(value: boolean) {
      emit('update:modelValue', value);
    },
  });

  watch(
    () => props.user,
    (user: User | null) => {
      let out = {};
      for(let key in user)
      {
        out[key] = user[key];
        if(key == 'role')
          out[key] = { value: user.role, label: RolesDescription[user.role] }
      }

      userData.value = out;
      buttonSuccessTooltip.value = buttonSuccessLabel.value = user.id ? 'Изменить' : 'Создать';
      dialogMode = user.id ? 'update' : 'new';

    },
  );

  const cancel = () => {
    dialog.value = false;

    emit('onCancel');
  };

  const success = async () => {
    dialog.value = false;

    if(dialogMode == 'update')
      await apiUpdateUserInfo(userData.value);
    else await apiSignupUser(userData.value);
    emit('onSuccess', true);
  };
</script>

