<template>
  <q-btn
    color="primary"
    label="Добавить"
    style="margin-bottom: 10px"
    type="reset"
    @click="appendNewUser"
  />
  <q-table
    title="Пользователи"
    :rows="usersTable.rows"
    :columns="usersTable.columns"
    row-key="id"
  >
    <template v-slot:body-cell-edit="props">
      <q-td :props="props">
        <q-btn icon="edit" @click="onCancel(props.row)"></q-btn>
      </q-td>
    </template>
    <template v-slot:body-cell-delete="props">
      <q-td :props="props">
        <q-btn icon="delete" @click="onCancel(props.row)"></q-btn>
      </q-td>
    </template>
  </q-table>
  <q-dialog
    v-model="isOpen">
      <q-card style="width: 500px; padding: 10px;">
        <q-card-section>
          Добавить нового пользователя
        </q-card-section>
        <q-card-section>
          <q-input
            v-model="userData.name"
            :rules="[ requiredStringRule ]"
            label="Имя"
          />
          <q-input
            v-model="userData.secondName"
            :rules="[ requiredStringRule ]"
            label="Фамилия"
          />
          <q-input
            v-model="userData.email"
            :rules="[ requiredStringRule ]"
            label="Email"
          />
          <q-select
            v-model="userData.role"
            :options="roleList"
            :rules="[ requiredSelectRule ]"
            label="Роль"
          />
          <q-select
            v-if = "userData.role?.value == Roles.Owner"
            v-model="userData.owner"
            :options="ownerList"
            :rules="[ requiredSelectRule ]"
            label="Предприятие"
          />
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
          <q-input
            v-model="userData.confirmPassword"
            :rules="[ requiredStringRule, (v) => requiredPasswordRule(v, userData.password) ]"
            :type="isPwd ? 'password' : 'text'"
            label="Подтверждение пароля"
          >
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>
        </q-card-section>
        <q-card-section>
          <q-btn
            color="primary float-right"
            label="Зарегистрировать"
            :disable="disabled"
            style="margin-top: 20px"
            @click="registration"
          />
          <q-btn
            color="white float-right"
            text-color="black"
            label="Отмена"
            style="margin-top: 20px; margin-right: 10px;"
            @click="onCancelModal"
          />
        </q-card-section>
      </q-card>
  </q-dialog>
</template>

<script setup lang="ts">

  import { computed, ref } from 'vue';
  import AuthService from '../../services/auth.service';
  import { requiredStringRule, requiredPasswordRule, requiredSelectRule } from '../../common/rules';
  import { Roles } from '../../constants';
  import { ownerStore } from '../../stores';
  import { storeToRefs } from 'pinia';

  const userData = ref({
    name: '',
    secondName: '',
    email: '',
    role: '',
    owner: '',
    password: '',
    confirmPassword: '',
  });

  let isPwd = ref(true);

  const roleList = ref([
    { value: Roles.Admin, label: 'Администратор' },
    { value: Roles.Government, label: 'Правительственный персонал' },
    { value: Roles.Owner, label: 'Собственник компании' },
  ])

  async function registration() {
    const response = await AuthService.registration({
      name: userData.value.name,
      second_name: userData.value.secondName,
      email: userData.value.email,
      role: userData.value.role.value,
      owner: userData.value.owner.value,
      password: userData.value.password
    });
    if(response) isOpen.value = false;
  }
  /**
   * Отправить запрос можно только при наличии всех значений
   */
  const disabled = computed(() => {
    return !userData.value.name
      || !userData.value.secondName
      || !userData.value.email
      || !userData.value.role
      || (userData.value.role?.value == Roles.Owner && !userData.value.owner)
      || !userData.value.password
      || !userData.value.confirmPassword
      || userData.value.password !== userData.value.confirmPassword;
  })
  const usersTable: any = {
    columns: [
      { name: 'name', align: 'center', label: 'Имя', field: 'name', sortable: true },
      { name: 'second_name', align: 'center', label: 'Имя', field: 'second_name', sortable: true },
      { name: 'email', align: 'center', label: 'email', field: 'email', sortable: true },
      { name: 'edit', label: '' },
      { name: 'delete', label: '' },
    ],
    rows: []
  }

  const isOpen = ref(false);

  function appendNewUser()
  {
    isOpen.value = true;
    userData.value = {
      name: '',
      secondName: '',
      email: '',
      role: '',
      owner: '',
      password: '',
      confirmPassword: '',
    };
  }

  function onCancelModal()
  {
    isOpen.value = false
  }

  const { ownerList } = storeToRefs(ownerStore());
  async function onApply(e: any)
  {
    console.log('Apply', e)
  }
  async function onCancel(e: any)
  {
    console.log('Cancel', e)
  }
</script>
