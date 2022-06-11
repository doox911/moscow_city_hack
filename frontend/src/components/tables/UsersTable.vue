<template>
  <q-btn
    color="primary"
    label="Добавить"
    style="margin-bottom: 10px"
    type="reset"
  />
  <q-table
    title="Пользователи"
    :rows="usersTable.rows"
    :columns="usersTable.columns"
    row-key="id"
  >
    <template v-slot:body-cell-edit="props">
      <q-td :props="props">
        <q-btn icon="edit" @click="onCancel(props.row)"></q-btn>
      </q-td>
    </template>
    <template v-slot:body-cell-delete="props">
      <q-td :props="props">
        <q-btn icon="delete" @click="onCancel(props.row)"></q-btn>
      </q-td>
    </template>
  </q-table>
</template>

<script setup lang="ts">

  const usersTable: any = {
    columns: [
      { name: 'name', align: 'center', label: 'Имя', field: 'name', sortable: true },
      { name: 'second_name', align: 'center', label: 'Имя', field: 'second_name', sortable: true },
      { name: 'email', align: 'center', label: 'email', field: 'email', sortable: true },
      { name: 'edit', label: '' },
      { name: 'delete', label: '' },
    ],
    rows: []
  }

  for(let i = 0; i < 100; i++)
  {
    usersTable.rows.push({
      name: i,
      second_name: i,
      email: i,
    })
  }

  async function onApply(e: any)
  {
    console.log('Apply', e)
  }
  async function onCancel(e: any)
  {
    console.log('Cancel', e)
  }
</script>
