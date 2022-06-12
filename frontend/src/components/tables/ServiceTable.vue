<template>
  <div class="q-pa-none">
    <h5 class="q-ma-xs q-pl-md non-selectable text-grey-9">
      Услуги
    </h5>
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
      rows-per-page-label="Услуг на странице"
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
              hover-color="primary"
              @click="openEditDialog(props.row)"
            />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-additional_info="props">
        <q-td :props="props">
          <div class="col">
            <div style="width:200px" class="customEllipsis">{{ props.row.additional_info }}</div>
          </div>
        </q-td>
      </template>
      <template v-slot:top-left>
        <div class = "row">
          <SelectCounterparty
            v-if = "isAttach"
            style = "padding: 0; margin-left: 5px;"
            v-model="selectCounterparty"
            button-text="Привязать услугу"
            @on-success="onCounterpartyAttachServices"
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
  <ServiceDialog 
    v-model="dialog" 
    v-model:service="selectService"
    :successButton="false"
    :resetButton="false"
    @on-success="emitOnRequest"
  />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  /**
   * Api
   */
  import { Service } from 'Src/api/service';

  /**
   * Common
   */
  import { 
    selectedRowsLabel,
    paginationLabel,
    getDefaultService,
    setDateAndTimeToDateTimeComponent,
    getDefaultCounterparty
  } from 'Src/common';

  /**
   * Components
   */
  import IconBtn from 'Components/common/IconBtn.vue'
  import ServiceDialog from 'Components/service/ServiceDialog.vue';
  import SelectCounterparty from 'Components/counterparty/SelectCounterparty.vue';

  /**
   * Types
   */
  import type { QTableOnRequestProps, ImportSortColoumn } from 'src/types';
  import type { QTableProps } from 'quasar';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from '../../stores';
  import { apiCounterpartyAttachServices, Counterparty } from '../../api/counterparty';

  const { allUser } = storeToRefs(userStore());

  const dialog = ref(false);

  const searchText = ref('');

  type Button = {
    color: string;
    event: 'visibility';
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
      field: 'name',
      label: 'Название',
      name: 'name',
      sortable: true,
    },
    {
      align: 'left',
      field: 'additional_info',
      label: 'Информация',
      name: 'additional_info',
      sortable: true,
    },
    {
      align: 'left',
      field: 'code',
      label: 'Код',
      name: 'code',
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
      color: 'apply',
      event: 'visibility',
      icon: 'visibility',
      tooltip: 'Посмотреть',
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
      selected?: Service[];
      service?: Service[];
      rowsPerPage?: number;
      isAttach: boolean;
      isSearch: boolean;
    }>(),
    {
      loading: false,
      rowsNumber: 0,
      rowsPerPage: 10,
      selected: () => [],
      service: () => [],
      isAttach: true,
      isSearch: true
    },
  );

  const rows = computed(() => props.service);

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
    set(v: Service[]) {
      return emit('update:selected', v);
    },
  });

  watch(
    () => props.rowsNumber,
    (v: number) => {
      pagination.value.rowsNumber = v;
    },
  );

  const selectService = ref<Service>(getDefaultService());
  const selectCounterparty = ref<Counterparty>(getDefaultCounterparty());

  function openEditDialog(value: Service) {
    dialog.value = true;

    selectService.value = { ...value };
  }

  async function onCounterpartyAttachServices()
  {
    apiCounterpartyAttachServices(selectCounterparty.value, props.selected.map(service => service.id));
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

    emitOnRequest()
  }

  function emitOnRequest()
  {
    let columns: ImportSortColoumn = {};
    
    if(pagination.value.sortBy){
      columns[pagination.value.sortBy] = pagination.value.descending ? 'desc' : 'asc';
    }

    emit('onRequest', {
      page: pagination.value.page,
      size: pagination.value.rowsPerPage,
      columns,
      searchText: searchText.value
    });
  }

</script>

<style scoped>
  .customEllipsis {
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    overflow: hidden !important;
  }
</style>