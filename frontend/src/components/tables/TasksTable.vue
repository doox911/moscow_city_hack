<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs non-selectable text-grey-9">Задачи</h5>
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
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="col q-gutter justify-center">
            <IconBtn
              v-for="(button, index) of buttons"
              :key="index"
              :color="button.color"
              :disabled="!props.row.is_moderated"
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
  <TaskEventLogDialog v-model="task_event_dialog" :task="selected_task" />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue'

  /**
   * Common
   */
  import { selectedRowsLabel, paginationLabel} from 'Src/common'

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
      label: 'Статус',
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
      label: 'Статус',
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
      tooltip: 'Отмена',
    },
    {
      color: 'green',
      event: 'onApply',
      icon: 'ok',
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

  const task_event_dialog = ref(false);

  const rows = computed(() => props.tasks);

  const selected_task = ref<Task | undefined>(undefined);

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
