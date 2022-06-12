<template>
  <div class="q-pa-sm">
    <h3 class="q-my-sm non-selectable text-grey-9">Поиск компаний, продукции и услуг</h3>
    <div class="row q-pt-sm">
      <div class="col-10">
        <q-input
          v-model="search"
          :loading="loading"
          outlined
          placeholder="Введите поисковой запрос"
          @update:model-value="startSearch"
          debounce="500"
        />
      </div>
      <div class="col-2" style="padding-left:10px;">
        <SelectOkved v-model="selectedOKVED" outlined/>
      </div>
    </div>
    <div class="search-result" style="padding:10px;">
      <div v-if="resultSearch.companies.length" class="row">
        <div class="col-12">
          <p class="text-h5">Компании</p>
        </div>
        <div class="col-12">
          <div class="row" style="gap:10px;">
            <CounterpartyElement
              v-for="counterparty in resultSearch.companies"
              :counterparty="counterparty"
            />
          </div>
        </div>
      </div>
      <div v-if="resultSearch.goods.length" class="row">
        <div class="col-12">
          <p class="text-h5">Товары</p>
        </div>
        <div class="col-12">
          <div class="row" style="gap:10px;">
            <GoodElement
              v-for="good in resultSearch.goods"
              :good="good"
            />
          </div>
        </div>
      </div>
      <div v-if="resultSearch.services.length" class="row">
        <div class="col-12">
          <p class="text-h5">Услуги</p>
        </div>
        <div class="col-12">
          <div class="row" style="gap:10px;">
            <div v-for="service in resultSearch.services">
              <q-chip icon="home_repair_service" color="deep-orange" text-color="white">{{ service.name }}</q-chip>
            </div>
          </div>
        </div>
      </div>
      <div v-if="isEmpty">
        <p class="text-h5">Запрос не дал результатов</p>
      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Components
   */
  import SelectOkved from 'Components/SelectOkved.vue';
  import GoodElement from '../components/good/GoodElement.vue';
  import CounterpartyElement from '../components/counterparty/CounterpartyElement.vue';

  /**
   * Hooks
   */
  import { useUserSearchPageGuard } from 'Src/hooks';

  /**
   * Types
   */
  import type { OKVED } from 'Src/api';
  import { AllEntity, apiGlobalSearch } from '../api/search';

  useUserSearchPageGuard();

  const search = ref('');

  const loading = ref(false);

  const isEmpty = ref(false);

  const selectedOKVED = ref({
    code: '',
    name: '',
    additional_info: '',
  });

  const resultSearch = ref<AllEntity>({
    companies: [],
    goods: [],
    services: []
  })

  async function startSearch()
  {
    if(!search.value) {
      isEmpty.value = false;
      resultSearch.value.companies = [];
      resultSearch.value.goods = [];
      resultSearch.value.services = [];
      return;
    }

    loading.value = true;

    const { companies, goods, services } = await apiGlobalSearch(search.value);
    resultSearch.value.companies = companies;
    resultSearch.value.goods = goods;
    resultSearch.value.services = services;

    isEmpty.value = !companies.length && !goods.length && !services.length;

    loading.value = false;
  }

</script>

<style scoped>
  .search-result > .row:nth-child(n+2)
  {
    margin-top:10px;
  }
</style>