<template>
  <q-page class="q-ma-md">
    <div class="row">
      <div class="col">
        <TasksTable :tasks="tasks" :loading="loading"/>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <UsersTable />
      </div>
    </div>
    <div class="row" style="margin-top:10px;">
      <div class="col">
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
  import { apiCounterparties, Counterpart } from '../api/counterparty';

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
  const counterpart = ref<Counterpart[]>([]);

  const counterpartPagination = {
    rowsNumber: 0,
    rowsPerPage: 10
  }

  const loading = ref(false)

  function onRequestOwner(e: any)
  {
    console.log(e)
  }

  onMounted(async () => {
    loading.value = true;

    tasks.value = await apiTasks({
      params: {
        item_per_page: 15,
        filter: {
          search_string: '',
          columns: {},
        }
      }
    });
    const { data } = await apiCounterparties({
      params: {
        item_per_page: counterpartPagination.rowsPerPage,
        filters: {
          search_string: '',
          columns: {
            name: 'asc',
            inn: 'desc'
          }
        }
      }
    });
    counterpart.value = data;
    loading.value = false;
  })
</script>
