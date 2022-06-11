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
          <q-select v-model="selected_company" :options="counterparties" label="Выбрать компанию" />
        </div>
      </div>
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { onMounted, ref } from 'vue';

  /**
   * Api
   */
  import { apiCounterparties, Counterparty } from 'Src/api/counterparty';

  /**
   * Common
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue'

  let page = 1;

  const dialog = ref(false);

  const filter = ref('');

  const counterparties = ref<Counterparty[]>([]);

  const selected_company = ref<Counterparty | null>(null);

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

  onMounted(async () => {
    counterparties.value = await apiCounterparties({
      params: {
        item_per_page: 15,
        page,
        filters: {
          search_string: '',
          columns: {}
        }
      }
    });
  });

</script>

<style lang="scss" scoped>
</style>
