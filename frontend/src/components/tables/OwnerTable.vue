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
      rows-per-page-label="Предприятий на странице"
      selection="multiple"
      @request="onRequest"
    >

    </q-table>
  </div>
  <TaskEventLogDialog v-model="task_event_dialog" :task="selected_task" />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Common
   */
  import { selectedRowsLabel, paginationLabel} from 'Src/common'

  /**
   * Types
   */
  import type { QTableOnRequestProps } from 'src/types';
  import type { QTableProps } from 'quasar';
  import { Counterparty } from 'Src/api/counterparty';
  import { setDateAndTimeToDateTimeComponent } from 'Src/common';


  const columns: QTableProps['columns'] = [
    {
      align: 'center',
      field: 'user_id',
      label: 'Пользователь',
      name: 'user_id',
      sortable: true,
    },
    {
      align: 'left',
      field: 'name',
      label: 'Название',
      name: 'name',
      sortable: true,
    },
    {
      align: 'left',
      field: 'full_name',
      label: 'Полное название',
      name: 'full_name',
      sortable: true,
    },
    {
      align: 'left',
      field: 'inn',
      label: 'ИНН',
      name: 'inn',
      sortable: true,
    },
    {
      align: 'left',
      field: 'ogrn',
      label: 'ОГРН',
      name: 'ogrn',
      sortable: true,
    },
    {
      align: 'left',
      field: 'address',
      label: 'Адрес',
      name: 'address',
      sortable: true,
    },
    {
      align: 'left',
      field: 'email',
      label: 'Почта',
      name: 'email',
      sortable: true,
    },
    {
      align: 'left',
      field: 'phone',
      label: 'телефон',
      name: 'phone',
      sortable: true,
    },
    {
      align: 'left',
      field: 'site',
      label: 'сайт',
      name: 'site',
      sortable: true,
    },
    {
      align: 'center',
      field: 'created_at',
      label: 'создано',
      name: 'created_at',
      format: (val) => setDateAndTimeToDateTimeComponent(val),
      sortable: true,
    },
    {
      align: 'center',
      field: 'updated_at',
      label: 'обновлено',
      name: 'updated_at',
      format: (val) => setDateAndTimeToDateTimeComponent(val),
      sortable: true,
    },
  ];

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
      selected?: Counterparty[];
      counterpart?: Counterparty[];
      rowsPerPage?: number;
    }>(),
    {
      loading: false,
      rowsNumber: 0,
      rowsPerPage: 10,
      selected: () => [],
      counterpart: () => [],
    },
  );

  const task_event_dialog = ref(false);

  const rows = computed(() => props.counterpart);

  const selected_task = ref<Counterparty | undefined>(undefined);

  const pagination = ref({
    sortBy: '',
    descending: true,
    page: 1,
    rowsPerPage: props.rowsPerPage,
    rowsNumber: props.rowsNumber,
  });

  const s = computed({
    get() {
      return props.selected;
    },
    set(v: Counterparty[]) {
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