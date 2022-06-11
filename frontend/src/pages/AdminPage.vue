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
        <OwnerTable />
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

  const tasks = ref<Task[]>([])

  const loading = ref(false)

  onMounted(async () => {
    loading.value = true;

    tasks.value = await apiTasks();

    loading.value = false;
  })
</script>
