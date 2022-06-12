<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">Предприятия</h5>
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
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="col q-gutter justify-center">
            <template v-for="(button, index) of buttons" :key="index">
              <IconBtn
                v-if="
                  button.event != 'map' ||
                  (button.event == 'map' && props.row.latitude)
                "
                :color="button.color"
                :icon="button.icon"
                :tooltip-text="button.tooltip"
                :disabled="button.event=='delete'"
                hover-color="primary"
                @click="onClick(button.event, { ...props.row })"
              />
            </template>
          </div>
        </q-td>
      </template>
      <template v-slot:top-left>
        <q-btn
          :loading="loading"
          color="primary"
          label="Создать предприятие"
          @click="appendNewCounterparty"
        />
      </template>
      <template v-slot:top-right>
        <q-input
          v-model="searchText"
          borderless
          debounce="300"
          dense
          placeholder="Search"
          @update:model-value="emitOnRequest"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
  </div>
  <CounterpartyDialog
    v-model="dialog"
    v-model:counterparty="selectCounterparty"
    @on-success="emitOnRequest"
  />
  <MapDialog
    v-model="mapDialog"
    v-model:coordinate="coordinate"
    :successButton="false"
    :resetButton="false"
  />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Api
   */
  import { Counterparty } from 'Src/api/counterparty';

  /**
   * Common
   */
  import {
    selectedRowsLabel,
    paginationLabel,
    getDefaultCounterparty,
    setDateAndTimeToDateTimeComponent,
  } from 'Src/common';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';
  import CounterpartyDialog from 'Components/counterparty/CounterpartyDialog.vue';
  import MapDialog from 'Components/counterparty/MapDialog.vue';

  /**
   * Types
   */
  import type { QTableOnRequestProps, ImportSortColumn } from 'src/types';
  import type { QTableProps } from 'quasar';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia';
  import { userStore } from '../../stores';

  interface Coordinate {
    lat: number;
    lon: number;
    title?: string;
  }
  const { allUser } = storeToRefs(userStore());

  const dialog = ref(false);
  const mapDialog = ref(false);

  const searchText = ref('');
  const coordinate = ref<Coordinate>({ lat: 55.751244, lon: 37.618423 });

  type Button = {
    color: string;
    event: 'edit' | 'delete' | 'map';
    icon: string;
    tooltip: string;
  };

  const columns: QTableProps['columns'] = [
    {
      align: 'center',
      field: 'user_id',
      label: 'Пользователь',
      name: 'user_id',
      format: (val) =>
        allUser.value.filter((user) => user.id == val).pop()?.name,
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
    {
      align: 'center',
      field: 'data_source_item_url',
      label: 'Источник',
      name: 'data_source_item_url',
      sortable: false,
    },
    {
      align: 'center',
      field: 'description',
      label: 'Описание',
      name: 'description',
      sortable: false,
    },
    {
      align: 'center',
      field: 'legal_address',
      label: 'Юридический адрес',
      name: 'legal_address',
      sortable: false,
    },
    {
      align: 'center',
      field: 'number_of_employees',
      label: 'Количество сотрудников',
      name: 'number_of_employees',
      sortable: false,
    },
    {
      align: 'center',
      field: 'authorized_capital',
      label: 'Уставной капитал',
      name: 'authorized_capital',
      sortable: false,
    },
    {
      align: 'center',
      field: 'registration_date',
      label: 'Дата регистрации',
      name: 'registration_date',
      format: (val) => {
        if (val) {
          const [ y, m, d ] = val.split('-');

          return `${d}.${m}.${y}`;
        }

        return val;
      },
      sortable: false,
    },
    {
      align: 'center',
      field: 'keywords_for_search',
      label: 'Уставной капитал',
      name: 'keywords_for_search',
      format: (val) => {
        if (val !== null) {
          return Object.entries(val)
            .map((e) => e[1])
            .join(', ');
        }

        return val;
      },
      sortable: false,
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
    {
      color: 'positive',
      event: 'map',
      icon: 'public',
      tooltip: 'Посмотреть на карте',
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

  const rows = computed(() => props.counterpart);

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

  const selectCounterparty = ref<Counterparty>(getDefaultCounterparty());

  function openEditDialog(value: Counterparty) {
    dialog.value = true;

    selectCounterparty.value = value;
  }
  function openMapDialog(value: Counterparty) {
    mapDialog.value = true;

    coordinate.value = {
      lat: value.latitude || 55.751244,
      lon: value.longitude || 37.618423,
      title: value.name,
    };
  }

  function appendNewCounterparty() {
    dialog.value = true;

    selectCounterparty.value = getDefaultCounterparty();
  }

  function onClick(event: Button['event'], props: Counterparty) {
    switch (event) {
    case 'edit':
      return openEditDialog(props);
    case 'map':
      return openMapDialog(props);
    }
  }

  function onRequest(ps: QTableOnRequestProps) {
    const { page, rowsPerPage, sortBy, descending } = ps.pagination;

    pagination.value = {
      ...pagination.value,
      page,
      rowsPerPage,
      sortBy,
      descending,
    };

    emitOnRequest();
  }

  function emitOnRequest() {
    let columns: ImportSortColumn = { 'name': 'desc' };

    if (pagination.value.sortBy) {
      columns[pagination.value.sortBy] = pagination.value.descending
        ? 'desc'
        : 'asc';
    }

    emit('onRequest', {
      page: pagination.value.page,
      size: pagination.value.rowsPerPage,
      columns,
      searchText: searchText.value,
    });
  }
</script>
