<template>
  <DialogCommonWrapper 
    v-model="dialog" 
    button-text="Выбрать компанию"
    header-text="Выбрать компанию"
    @on-close="onClose"
    @on-reset="onReset"
    @on-success="onSuccess"
  >
    <div class="col">
      <div class="row">
        <div class="col">
          <q-input v-model="filter" placeholder="Фильтр по компаниям">
            <template v-slot:append>
              <q-icon name="search" @click="getCompany" class="cursor-pointer"/>
            </template>
          </q-input>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <q-select v-model="selected_company" :options="companies" label="Выбрать компанию" />
        </div>
      </div>
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';

  /**
   * Common
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue'


  const dialog = ref(false);

  const filter = ref('');

  const companies = ref([]);

  const selected_company = ref(null);

  async function getCompany() {
    console.log('getCompany');

  }

  const onClose = () => {
  };

  const onReset = () => {
    filter.value  = '';
  };

  const onSuccess = async () => {

  };

</script>

<style lang="scss" scoped>
</style>
