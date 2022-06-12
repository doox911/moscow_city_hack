<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">Товары</h5>
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
      rows-per-page-label="Товаров на странице"
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
              :icon="button.icon"
              :tooltip-text="button.tooltip"
              :disabled="button.event=='delete'"
              hover-color="primary"
              @click="openEditDialog(props.row)"
            />
          </div>
        </q-td>
      </template>
      <template v-slot:top-left>
        <div class="row">
          <q-btn
            :loading="loading"
            color="primary"
            label="Создать товар"
            @click="appendNewGood"
          />
          <SelectCounterparty
            v-if="isAttach"
            style="padding: 0; margin-left: 5px"
            v-model="selectCounterparty"
            button-text="Привязать товар"
            @on-success="onCounterpartyAttachGoods"
          />
        </div>
      </template>
      <template v-slot:top-right>
        <q-input
          v-if="isSearch"
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
  <GoodDialog
    v-model="dialog"
    v-model:good="selectGood"
    @on-success="emitOnRequest"
  />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Api
   */
  import { Good } from 'Src/api/good';

  /**
   * Common
   */
  import {
    selectedRowsLabel,
    paginationLabel,
    getDefaultGood,
    setDateAndTimeToDateTimeComponent,
    getDefaultCounterparty,
  } from 'Src/common';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue';
  import GoodDialog from 'Components/good/GoodDialog.vue';
  import SelectCounterparty from 'Components/counterparty/SelectCounterparty.vue';

  /**
   * Types
   */
  import type { QTableOnRequestProps, ImportSortColumn } from 'src/types';
  import type { QTableProps } from 'quasar';

  /**
   * Store
   */
  import {
    apiCounterpartyAttachGoods,
    Counterparty,
  } from '../../api/counterparty';

  const dialog = ref(false);

  const searchText = ref('');

  type Button = {
    color: string;
    event: 'edit' | 'delete';
    icon: string;
    tooltip: string;
  };

  const columns: QTableProps['columns'] = [
    {
      align: 'center',
      field: 'id',
      label: 'id',
      name: 'id',
      sortable: true,
    },
    {
      align: 'left',
      field: 'brand',
      label: 'Брэнд',
      name: 'brand',
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
      rowsNumber?: number;
      selected?: Good[];
      good?: Good[];
      rowsPerPage?: number;
      isAttach?: boolean;
      isSearch?: boolean;
    }>(),
    {
      loading: false,
      rowsNumber: 0,
      rowsPerPage: 10,
      selected: () => [],
      good: () => [],
      isAttach: true,
      isSearch: true,
    },
  );

  const rows = computed(() => props.good);

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
    set(v: Good[]) {
      return emit('update:selected', v);
    },
  });

  watch(
    () => props.rowsNumber,
    (v: number) => {
      pagination.value.rowsNumber = v;
    },
  );

  const selectGood = ref<Good>(getDefaultGood());
  const selectCounterparty = ref<Counterparty>(getDefaultCounterparty());

  function openEditDialog(value: Good) {
    dialog.value = true;

    selectGood.value = { ...value };
  }

  function appendNewGood() {
    dialog.value = true;
    selectGood.value = getDefaultGood();
  }
  async function onCounterpartyAttachGoods() {
    apiCounterpartyAttachGoods(selectCounterparty.value, props.selected);
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
