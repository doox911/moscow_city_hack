<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">Пользователи</h5>
    <div>
      <UserDialog
        v-model="dialog"
        v-model:user="selectUser"
        :loading="loading"
        :use-reset="false"
        @on-success="onSuccess"
      />
    </div>
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
    </q-table>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Common
   */
  import {
    selectedRowsLabel,
    paginationLabel,
    getDefaultUser,
  } from 'Src/common';

  /**
   * Store
   */
  import { User, userStore } from '../../stores';
  import { storeToRefs } from 'pinia';

  /**
   * Types
   */
  import type { QTableProps } from 'quasar';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';
  import UserDialog from 'Components/user/UserDialog.vue';

  const { loadAllUser } = userStore();

  const { allUser } = storeToRefs(userStore());

  const dialog = ref(false);

  const selectUser = ref<User>({ ...getDefaultUser() });

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
      sortable: true,
    },
    {
      name: 'second_name',
      align: 'left',
      label: 'Фамилия',
      field: 'second_name',
      sortable: true,
    },
    {
      name: 'email',
      align: 'left',
      label: 'email',
      field: 'email',
      sortable: true,
    },
    {
      name: 'role',
      align: 'left',
      label: 'Роль',
      field: 'role',
      sortable: true,
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

  defineEmits([
    'onSearch',
    'onRequest',
    'onCancel',
    'onApply',
    'update:selected',
  ]);

  withDefaults(
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

  async function onSuccess() {
    loading.value = true;

    await loadAllUser();

    loading.value = false;
  }

  async function onRequest() {
    loading.value = true;

    await loadAllUser();

    loading.value = false;
  }
</script>
