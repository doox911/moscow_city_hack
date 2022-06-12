<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">Задачи</h5>
    <q-table
      v-model:pagination="pagination"
      v-model:selected="s"
      :columns="columns"
      :loading="loading"
      :pagination-label="paginationLabel"
      :rows="rows"
      :selected-rows-label="selectedRowsLabel"
      binary-state-sort
      class="no-shadow"
      no-data-label="Нет данных"
      row-key="id"
      rows-per-page-label="Задач на странице"
      selection="multiple"
      @request="onRequest"
    >
      <template v-slot:body-cell-is_moderated="props">
        <q-td :props="props">
          {{ props.row.is_moderated === 0 ? 'В процессе' : 'Рассмотрена' }}
        </q-td>
      </template>
      <template v-slot:body-cell-is_accepted="props">
        <q-td :props="props">
          {{
            props.row.is_moderated === 0
              ? 'На рассмотрении'
              : props.row.is_accepted
              ? 'Принята'
              : 'Отклонена'
          }}
        </q-td>
      </template>
      <template
        v-if="user.role === Roles.Admin"
        v-slot:body-cell-actions="props"
      >
        <q-td :props="props">
          <div class="col q-gutter justify-center">
            <IconBtn
              v-for="(button, index) of buttons"
              :key="index"
              :color="button.color"
              :disabled="props.row.is_moderated === 1"
              :icon="button.icon"
              :tooltip-text="button.tooltip"
              hover-color="primary"
              @click="$emit(button.event, { ...props.row })"
            />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';

  /**
   * Common
   */
  import { selectedRowsLabel, paginationLabel } from 'Src/common';

  /**
   * Constants
   */
  import { Roles } from 'Src/constants';

  /**
   * Store
   */
  import { userStore } from 'Stores/userStore';

  /**
   * Types
   */
  import type { QTableOnRequestProps } from 'src/types';
  import type { QTableProps } from 'quasar';
  import type { Task } from 'Src/api/task';

  type Button = {
    color: string;
    event: 'onCancel' | 'onApply';
    icon: string;
    tooltip: string;
  };

  const columns: QTableProps['columns'] = [
    {
      align: 'center',
      field: 'user_id',
      label: 'Пользователь',
      name: 'user_id',
      sortable: true,
    },
    {
      align: 'center',
      field: 'is_moderated',
      label: 'Прогресс рассмотрения',
      name: 'is_moderated',
      sortable: true,
    },
    {
      align: 'center',
      field: 'is_accepted',
      label: 'Статус',
      name: 'is_accepted',
      sortable: true,
    },
    {
      align: 'center',
      field: 'comment',
      label: 'Комментарий',
      name: 'comment',
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
      color: 'red',
      event: 'onCancel',
      icon: 'cancel',
      tooltip: 'Отклонить',
    },
    {
      color: 'green',
      event: 'onApply',
      icon: 'done',
      tooltip: 'Принять',
    },
  ];

  const emit = defineEmits([
    'onApply',
    'onCancel',
    'onRequest',
    'update:selected',
  ]);

  const props = withDefaults(
    defineProps<{
      loading?: boolean;
      rowsNumber?: number;
      selected?: Task[];
      tasks?: Task[];
    }>(),
    {
      loading: false,
      rowsNumber: 0,
      selected: () => [],
      tasks: () => [],
    },
  );

  const { user } = userStore();

  const rows = computed(() => props.tasks);

  const pagination = ref({
    sortBy: '',
    descending: true,
    page: 1,
    rowsPerPage: 10,
    rowsNumber: props.rowsNumber,
  });

  const s = computed({
    get() {
      return props.selected;
    },
    set(v: Task[]) {
      return emit('update:selected', v);
    },
  });

  watch(
    () => props.rowsNumber,
    (v: number) => {
      pagination.value.rowsNumber = v;
    },
  );

  function onRequest(ps: QTableOnRequestProps) {
    // eslint-disable-next-line object-curly-newline
    const { page, rowsPerPage, sortBy, descending } = ps.pagination;

    pagination.value = {
      ...pagination.value,
      page,
      rowsPerPage,
      sortBy,
      descending,
    };

    emit('onRequest', {
      page: page,
      size: rowsPerPage,
      sort: [`${sortBy},${descending ? 'desc' : 'asc'}`],
    });
  }
</script>
