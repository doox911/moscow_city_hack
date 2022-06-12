<template>
  <q-page class="q-ma-md">
    <div class="row">
      <div class="col">
        <TasksTable 
          :tasks="tasks" 
          :loading="loading"
          @on-apply="onTaskApply"
          @on-cancel="onTaskCancel"
      />
      </div>
    </div>
    <div class="row">
      <div class="col">
        <UsersTable />
      </div>
    </div>
    <div class="row">
      <div class="col">
        <OwnerTable
          :counterpart="counterpartRef.data"
          :loading="counterpartRef.loading"
          :rowsNumber="counterpartRef.rowsNumber"
          @on-request="onRequestOwner"
        />
      </div>
    </div>
    <div class="row">
      <div class="col">
        <GoodsTable
          :counterpart="goodsRef.data"
          :loading="goodsRef.loading"
          :rowsNumber="goodsRef.rowsNumber"
          @on-request="onRequestOwner"
        />
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
  import { onMounted, ref } from 'vue'

  /**
   * Api
   */
  import { apiTasks } from 'Src/api/task';
  import { apiCounterparties } from 'Src/api/counterparty';
  import { apiGoods } from '../api/good';

  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';

  /**
   * Components
   */
  import TasksTable from '../components/tables/TasksTable.vue';
  import UsersTable from '../components/tables/UsersTable.vue';
  import OwnerTable from '../components/tables/OwnerTable.vue';
  import GoodsTable from '../components/tables/GoodsTable.vue';

  /**
   * Types
   */
  import type { Task } from 'Src/api/task';
  import type { ImportSortColoumn } from 'Src/types';

  useUserPageGuard();

  const tasks = ref<Task[]>([]);

  const counterpartRef: any = ref({
    data: [],
    rowsNumber: 0,
    loading: false
  });

  const goodsRef: any = ref({
    data: [],
    rowsNumber: 0,
    loading: false
  });

  const loading = ref(false)

  async function onRequestOwner({ page, size, columns, searchText }: { page: number, size: number, columns: ImportSortColoumn, searchText: string })
  {
    counterpartRef.value.loading = true;
    const { counterparties, total_rows } = await apiCounterparties({
      params: {
        page,
        item_per_page: size,
        filters: {
          search_string: searchText,
          columns
        }
      }
    });

    counterpartRef.value.rowsNumber = total_rows;
    counterpartRef.value.data = counterparties;
    counterpartRef.value.loading = false;
  }

  async function onRequestGoods({ page, size, columns, searchText }: { page: number, size: number, columns: ImportSortColoumn, searchText: string })
  {
    goodsRef.value.loading = true;
    const { counterparties, total_rows } = await apiGoods({
      params: {
        page,
        item_per_page: size,
        filters: {
          search_string: searchText,
          columns
        }
      }
    });

    goodsRef.value.rowsNumber = total_rows;
    goodsRef.value.data = counterparties;
    goodsRef.value.loading = false;
  }

  async function onTaskApply(t: Task) {
    console.log('onTaskApply', t);
  }

  async function onTaskCancel(t: Task) {
    console.log('onTaskCancel', t);
  }

  onMounted(async () => {
    loading.value = true;

    tasks.value = await apiTasks({
      params: {
        item_per_page: 15,
        filter: {
          search_string: '',
          columns: {},
        },
      }
    });

    onRequestOwner({
      page: 1,
      size: 10,
      columns: {},
      searchText: ''
    });
    onRequestGoods({
      page: 1,
      size: 10,
      columns: {},
      searchText: ''
    });

    loading.value = false;
  })
</script>
