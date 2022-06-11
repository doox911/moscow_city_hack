<template>
  <q-btn
    color="primary"
    label="Добавить"
    style="margin-bottom: 10px"
    type="reset"
    :loading="loading"
    @request="onRequest"
    @click="appendNewOwner"
  />
  <q-table
    title="Предприятия"
    :columns="columns"
    :rows="rows"
    :pagination-label="paginationLabel"
    :selected-rows-label="selectedRowsLabel"
    row-key="id"
  >
    <template v-slot:body-cell-edit="props">
      <q-td :props="props">
        <q-btn icon="edit" @click="onEdit(props.row)"></q-btn>
      </q-td>
    </template>
    <template v-slot:body-cell-delete="props">
      <q-td :props="props">
        <q-btn icon="delete" @click="onDelete(props.row)"></q-btn>
      </q-td>
    </template>
  </q-table>
</template>

<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Store
   */
  import { ownerStore, User } from '../../stores';
  import { storeToRefs } from 'pinia';

  /**
   * Common
   */
  import { selectedRowsLabel, paginationLabel} from 'Src/common'
  import { apiGetAllOwner } from '../../api/owner';

  /** 
   * Открыть окно редактирования пользователя
   */
  function appendNewOwner() {
    /* isOpen.value = true;
    userData.value = {
      id: null,
      name: '',
      secondName: '',
      email: '',
      role: '',
      owner: '',
      password: '',
      confirmPassword: '',
    };
    modalMode.value = 'new'; */
  }

  const columns: any = [
    { name: 'name', align: 'center', label: 'Имя', field: 'name', sortable: true },
    { name: 'edit', label: '' },
    { name: 'delete', label: '' },
  ]
  const rows = ref([]);
  const loading = ref(false);
  const { ownerList } = storeToRefs(ownerStore());

  async function onRequest()
  {
    loading.value = true;

    const owner = await apiGetAllOwner();

    rows.value.splice(0, rows.value.length, ...owner);

    loading.value = false
  }
  onRequest();

  async function onApply(e: any)
  {
    console.log('Apply', e)
  }
  async function onCancel(e: any)
  {
    console.log('Cancel', e)
  }
</script>