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
    <div class="row" style="margin-top:10px;">
      <div class="col">
        <div class = "row">
          <q-input outlined v-model="searchText" label="Поиск" />
          <q-btn color="primary" label="Поиск"/>
        </div>
        <OwnerTable
          :counterpart="counterpart"
          :loading="loading"
          :rowsNumber="rowsNumber"
          @on-request="onRequestOwner"/>
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
  import { apiCounterparties, Counterparty } from '../api/counterparty';

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

  /**
   * Types
   */
  import type { Task } from 'Src/api/task';

  useUserPageGuard();

  const tasks = ref<Task[]>([]);

  const counterpart = ref<Counterparty[]>([]);

  const rowsNumber = ref(0);

  const rowsPerPage = ref(10);
  
  const searchText = ref('');

  const loading = ref(false)

  async function onRequestOwner({ page, size }: { page: number, size: number })
  {
    loading.value = true;
    const { counterparties, pages_count, total_rows } = await apiCounterparties({
      params: {
        page,
        item_per_page: size,
        filters: {
          search_string: searchText.value,
          columns: {
            name: 'asc',
            inn: 'desc'
          }
        }
      }
    });
    rowsPerPage.value = pages_count;
    rowsNumber.value = total_rows;
    counterpart.value = counterparties.data;
    loading.value = false;
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
    onRequestOwner({ page: 1, size: rowsPerPage.value })
    loading.value = false;
  })
</script>
