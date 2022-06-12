<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">
      Пользователи
    </h5>
    <q-table
      :columns="columns"
      :rows="allUser"
      :loading="loading"
      :pagination-label="paginationLabel"
      :selected-rows-label="selectedRowsLabel"
      class="no-shadow"
      row-key="id"
      rows-per-page-label="Пользователей на странице"
      @request="onRequest"
    >
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="col q-gutter justify-center">
            <IconBtn
              v-for="(button, index) of buttons"
              :key="index"
              :color="button.color"
              :icon="button.icon"
              :tooltip-text="button.tooltip"
              hover-color="primary"
              @click="openEditDialog(props.row)"
            />
          </div>
        </q-td>
      </template>
      <template v-slot:top-left>
        <q-btn
          :loading="loading"
          color="primary float-right"
          label="Добавить пользователя"
          @click="appendNewUser"
        />
      </template>
    </q-table>
  </div>
  <UserDialog 
    v-model="dialog" 
    v-model:counterparty="selectCounterparty"
    @on-success="emitOnRequest"
  />
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
  import { selectedRowsLabel, paginationLabel, getDefaultUser} from 'Src/common';

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
  import { QTableOnRequestProps } from '../../types';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue'
  import UserDialog from 'Components/user/UserDialog.vue';


  const { loadAllUser } = userStore();
  const { allUser } = storeToRefs(userStore());

  const dialog = ref(false);

  const searchText = ref('');

  const selectUser = ref<User>();

  const loading = ref(false);

  type Button = {
    color: string;
    event: 'edit' | 'delete';
    icon: string;
    tooltip: string;
  };
  
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
  
  const buttons: Button[] = [
    {
      color: 'warning',
      event: 'edit',
      icon: 'edit',
      tooltip: 'Редактировать',
    },
    {
      color: 'red',
      event: 'delete',
      icon: 'delete',
      tooltip: 'Удалить',
    },
  ];

  const emit = defineEmits([
    'onSearch',
    'onRequest',
    'onCancel',
    'onApply',
    'update:selected',
  ]);

  const props = withDefaults(
    defineProps<{
      loading?: boolean;
      user?: User[];
    }>(),
    {
      loading: false,
      user: () => [],
    },
  );

  /** 
   * Открыть окно редактирования пользователя
   */
  function openEditDialog(value: User) {
    dialog.value = true;

    selectUser.value = { ...value };
  }

  function appendNewUser() {
    dialog.value = true;

    selectUser.value = getDefaultUser();
  }

  async function onRequest(ps: QTableOnRequestProps) {
    const { page, rowsPerPage, sortBy, descending } = ps.pagination;

    loading.value = true;

    await loadAllUser();

    loading.value = false

  }
</script>
