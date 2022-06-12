<template>
  <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">Пользователи</h5>
  <q-table
    :columns="columns"
    :rows="allUser"
    :pagination-label="paginationLabel"
    :selected-rows-label="selectedRowsLabel"
    class="no-shadow"
    row-key="id"
    rows-per-page-label="Пользователей на странице"
  >
    <template v-slot:top-left>
      <q-btn
        :loading="loading"
        color="primary float-right"
        label="Добавить пользователя"
        @click="appendNewUser"
      />
    </template>
    <template v-slot:body-cell-actions="props">
      <q-td :props="props">
        <q-btn flat icon="edit" @click="onEdit(props.row)"></q-btn>
      </q-td>
    </template>
  </q-table>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';

  /**
   * Api
   */
  import { apiGetAllUsers, apiSignupUser, apiUpdateUserInfo } from 'Src/api/users';

  /**
   * Constants
   */
  import { Roles, RolesDescription } from 'Src/constants';

  /**
   * Common
   */
  import { selectedRowsLabel, paginationLabel} from 'Src/common';

  /**
   * Rules
   */
  import { requiredStringRule, requiredPasswordRule, requiredSelectRule } from 'Src/common/rules';

  /**
   * Store
   */
  import { ownerStore, User, userStore } from '../../stores';
  import { storeToRefs } from 'pinia';

  /**
   * Types
   */
  import type { QTableProps } from 'quasar';

  const { loadAllUser } = userStore();
  const { allUser } = storeToRefs(userStore());

  const userData = ref({
    id: null,
    patronymic: '',
    name: '',
    secondName: '',
    email: '',
    role: '',
    owner: '',
    password: '',
    confirmPassword: '',
  });

  let modalMode = ref<'new' | 'update'>('new');

  const roleList = ref([
    { value: Roles.Admin, label: RolesDescription[Roles.Admin] },
    { value: Roles.Government, label: RolesDescription[Roles.Government] },
    { value: Roles.Owner, label: RolesDescription[Roles.Owner] },
    { value: Roles.Guest, label: RolesDescription[Roles.Guest] },
  ])

  /**
   * Осуществляется запрос регистрации пользователя
   */
  async function registration() {
    const response = await apiSignupUser({
      second_name: userData.value.secondName,
      name: userData.value.name,
      patronymic: userData.value.patronymic,
      email: userData.value.email,
      role: userData.value.role.value,
      owner: userData.value.owner?.value,
      password: userData.value.password
    });
    if(response) {
      isOpen.value = false;
      onRequest();
    }
  }
  /**
   * Изменить пользователя
   */
  async function updateUser() {
    const response = await apiUpdateUserInfo({
      id: userData.value.id,
      second_name: userData.value.secondName,
      name: userData.value.name,
      patronymic: userData.value.patronymic,
      email: userData.value.email,
      role: userData.value.role.value,
      owner: userData.value.owner?.value,
      password: userData.value.password
    });

    if(response) {
      isOpen.value = false;
      onRequest();
    }
  }
  /**
   * Отправить запрос можно только при наличии всех значений
   */
  const disabled = computed(() => {
    if(modalMode.value == 'update') return false;
    return !userData.value.name
      || !userData.value.secondName
      || !userData.value.email
      || !userData.value.role
      || (userData.value.role?.value == Roles.Owner && !userData.value.owner)
      || !userData.value.password
      || !userData.value.confirmPassword
      || userData.value.password !== userData.value.confirmPassword;
  })

  /** 
   * Открыть окно редактирования пользователя
   */
  function appendNewUser() {
    isOpen.value = true;
    userData.value = {
      id: null,
      name: '',
      secondName: '',
      email: '',
      role: '',
      owner: '',
      password: '',
      confirmPassword: '',
    };
    modalMode.value = 'new';
  }

  /**
   * Закрыть модальное окно
   */
  function onCancelModal() {
    isOpen.value = false
  }
  /**
   * Заголовки таблицы
   */
  const columns: QTableProps['columns'] = [
    {
      name: 'name',
      align: 'left',
      label: 'Имя',
      field: 'name', 
      sortable: true
    },
    {
      name: 'second_name',
      align: 'left',
      label: 'Фамилия',
      field: 'second_name',
      sortable: true
    },
    {
      name: 'email',
      align: 'left',
      label: 'email',
      field: 'email',
      sortable: true
    },
    {
      name: 'role',
      align: 'left',
      label: 'Роль',
      field: 'role',
      sortable: true
    },
    {
      align: 'center',
      field: '',
      label: 'Управление',
      name: 'actions',
    },
  ];
  const isOpen = ref(false);
  let isPwd = ref(true);
  const loading = ref(false);
  const { ownerList } = storeToRefs(ownerStore());

  async function onRequest()
  {
    loading.value = true;

    await loadAllUser();

    loading.value = false
  }

  async function onEdit(user: User)
  {
    appendNewUser();
    userData.value = {
      ...user,
      role: { value: user.role, label: RolesDescription[user.role] }
    };
    modalMode.value = 'update';
  }
</script>
