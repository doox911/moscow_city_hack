<template>
  <q-page class="q-mx-md" style = "padding: 10px;">
    <div class='row justify-center' style = "gap: 10px;">
      <div class='col-12 col-md-3' style = "padding-top: 46px;">
        <TaskTable/>
      </div>
      <div class='col-12 col-md-3'>
        <UsersTable/>
      </div>
      <div class='col-12 col-md-3'>
        <OwnerTable/>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';
  /**
   * Components
   */
  import TaskTable from '../components/tables/TaskTable.vue';
  import UsersTable from '../components/tables/UsersTable.vue';
  import OwnerTable from '../components/tables/OwnerTable.vue';

  useUserPageGuard();
</script>
