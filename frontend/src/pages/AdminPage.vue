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
        <div class = "row" style = "justify-content: end;">
          <q-input borderless dense debounce="300" v-model="counterpartRef.searchText" placeholder="Search" @update:model-value="searchInCounterpart">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
        <OwnerTable
          :counterpart="counterpartRef.data"
          :loading="counterpartRef.loading"
          :rowsNumber="counterpartRef.rowsNumber"
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

  const counterpartRef: any = ref({
    data: [],
    rowsNumber: 0,
    rowsPerPage: 10,
    searchText: '',
    size: 10,
    loading: false
  })

  const loading = ref(false)

  async function onRequestOwner({ page, size, sort }: { page: number, size: number, sort: [string, string] })
  {
    const columns: any = {};
    if(sort)
    {
      sort.forEach(item => {
        let v: string[] = item.split(',');
        columns[v[0]] = v[1];
      })
    }
    counterpartRef.value.size = size;
    counterpartRef.value.loading = true;
    const { counterparties, total_rows } = await apiCounterparties({
      params: {
        page,
        item_per_page: size,
        filters: {
          search_string: counterpartRef.value.searchText,
          columns
        }
      }
    });
    counterpartRef.value.rowsNumber = total_rows;
    counterpartRef.value.data = counterparties.data;
    counterpartRef.value.loading = false;
  }
  function searchInCounterpart()
  {
    onRequestOwner({ page: 1, size: counterpartRef.value.size })
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
    onRequestOwner({ page: 1, size: counterpartRef.value.size });
    loading.value = false;
  })
</script>
