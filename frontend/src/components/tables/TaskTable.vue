<template>
  <q-table
    title="Задачи"
    :rows="tasksTable.rows"
    :columns="tasksTable.columns"
    row-key="id"
  >
    <template v-slot:body-cell-apply="props">
      <q-td :props="props">
        <q-btn icon="done" @click="onApply(props.row)"></q-btn>
      </q-td>
    </template>
    <template v-slot:body-cell-cancel="props">
      <q-td :props="props">
        <q-btn icon="do_disturb_alt" @click="onCancel(props.row)"></q-btn>
      </q-td>
    </template>
  </q-table>
</template>

<script setup lang="ts">

  const tasksTable: any = {
    columns: [
      { name: 'id', align: 'center', label: 'id', field: 'id', sortable: true },
      { name: 'greed', align: 'center', label: 'Товар', field: 'name', sortable: true },
      { name: 'apply', label: '' },
      { name: 'cancel', label: '' },
    ],
    rows: []
  }

  for(let i = 0; i < 100; i++)
  {
    tasksTable.rows.push({
      id: i,
      name: i,
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
