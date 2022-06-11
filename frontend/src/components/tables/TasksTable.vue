<template>
  <div class="q-pa-none">
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
      row-key="id"
      rows-per-page-label="Задач на странице"
      selection="multiple"
      @request="onRequest"
    >
      <template v-slot:header-selection="props">
        <q-checkbox :disable="loading" v-model="props.selected" />
      </template>
      <template v-slot:body-selection="props">
        <q-checkbox :disable="loading" v-model="props.selected" />
      </template>
      <template v-slot:body-cell-mask="props">
        <q-td :props="props">
          {{ getRegionName(props.row.mask) }}
        </q-td>
      </template>
      <template v-slot:body-cell-timeline="props">
        <q-td :props="props">
          {{ getTimelineName(props.row) }}
        </q-td>
      </template>
      <template v-slot:body-cell-state="props">
        <q-td :props="props">
          {{ getTaskStatus(props.row.state) }}
        </q-td>
      </template>
      <template v-slot:body-cell-container-count="props">
        <q-td :props="props">
          {{ getCountContainers(props.row) }}
        </q-td>
      </template>
      <template v-slot:body-cell-event-log="props">
        <q-td :props="props">
          <q-btn
            flat
            color="primary"
            label="Просмотр журнала"
            @click="onSelectViewEventLog(props.row)"
          />
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="col q-gutter justify-center">
            <IconBtn
              v-for="(button, index) of buttons"
              :key="index"
              :color="button.color"
              :icon="button.icon"
              :tooltip-text="button.tooltip"
              :disable="loading || button.disabled(props.row)"
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
   * Common
   */
  import {
    selectedRowsLabel,
    paginationLabel,
    getTaskStatus,
  } from 'src/common';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';
  import TaskEventLogDialog from 'Components/tasks/TaskEventLogDialog.vue';

  /**
   * Constants
   */
  import { TaskViewDTOStateEnum } from 'Services/api/openapi';

  /**
   * Store
   */
  import { useRegionStore } from 'Stores/useRegionStore';

  /**
   * Types
   */
  import type { QTableOnRequestProps } from 'src/types';
  import type { QTableProps } from 'quasar';
  import type { TaskViewDTO } from 'Services/api/openapi';

  type Button = {
    color: string;
    disabled: (v: TaskViewDTO) => boolean;
    event: 'onRemigrate' | 'onSelectedTimeline' | 'onStart' | 'onStop';
    icon: string;
    tooltip: string;
  };

  const columns: QTableProps['columns'] = [
    {
      align: 'center',
      field: 'mask',
      label: 'Номер кадастрового округа',
      name: 'mask',
      sortable: true,
    },
    {
      align: 'center',
      field: 'name',
      label: 'Кадастровый округ/район',
      name: 'name',
      sortable: true,
    },
    {
      align: 'center',
      field: '',
      label: 'Временной график',
      name: 'timeline',
    },
    {
      align: 'center',
      field: '',
      label: 'Статус задачи',
      name: 'state',
      sortable: true,
    },
    {
      align: 'center',
      field: '',
      label:
        'Контейнеров обработано/Успешно импортировано/Не удалось импортировать',
      name: 'container-count',
    },
    {
      align: 'center',
      field: '',
      label: 'Журнал событий',
      name: 'event-log',
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
      disabled: () => false,
      event: 'onSelectedTimeline',
      icon: 'calendar_month',
      tooltip: 'Выбрать временный график',
    },
    {
      color: 'red',
      disabled: (t: TaskViewDTO) =>
        // eslint-disable-next-line operator-linebreak
        t.state === TaskViewDTOStateEnum.Planned ||
        t.state === TaskViewDTOStateEnum.Running,
      event: 'onStop',
      icon: 'stop_circle',
      tooltip: 'Остановить',
    },
    {
      color: 'green',
      disabled: (t: TaskViewDTO) =>
        // eslint-disable-next-line operator-linebreak
        t.state === TaskViewDTOStateEnum.Stopped ||
        t.state === TaskViewDTOStateEnum.FinishedPartial,
      event: 'onStart',
      icon: 'play_circle_filled',
      tooltip: 'Запустить',
    },
    {
      color: 'warning',
      disabled: () => false,
      event: 'onRemigrate',
      icon: 'autorenew',
      tooltip: 'Перемиграция',
    },
  ];

  const { getRegionName } = useRegionStore();

  const emit = defineEmits([
    'onLoadCSV',
    'onRemigrate',
    'onRequest',
    'onSelectedTimeline',
    'onStart',
    'onStop',
    'update:selected',
  ]);

  const props = withDefaults(
    defineProps<{
      loading?: boolean;
      rowsNumber?: number;
      selected?: TaskViewDTO[];
      tasks?: TaskViewDTO[];
    }>(),
    {
      loading: false,
      rowsNumber: 0,
      selected: () => [],
      tasks: () => [],
    },
  );

  const task_event_dialog = ref(false);

  const selected_task = ref<TaskViewDTO | undefined>(undefined);

  const pagination = ref({
    sortBy: '',
    descending: true,
    page: 1,
    rowsPerPage: 10,
    rowsNumber: props.rowsNumber,
  });

  const rows = computed(() => props.tasks);

  const s = computed({
    get() {
      return props.selected;
    },
    set(v: TaskViewDTO[]) {
      return emit('update:selected', v);
    },
  });

  watch(
    () => props.rowsNumber,
    (v: number) => {
      pagination.value.rowsNumber = v;
    },
  );

  function getCountContainers(task: TaskViewDTO) {
    return `${task.containers}/${task.containersSuccess}/${task.containersError}`;
  }

  function getTimelineName(task: TaskViewDTO) {
    return task.timeline?.name || '';
  }

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
      page: page ? page - 1 : 0,
      size: rowsPerPage,
      sort: [`${sortBy},${descending ? 'desc' : 'asc'}`],
    });
  }

  function onSelectViewEventLog(task: TaskViewDTO) {
    selected_task.value = task;
    task_event_dialog.value = true;
  }
</script>
