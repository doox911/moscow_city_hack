<template>
  <div class="q-pa-sm">
    <h3 class="q-my-sm non-selectable text-grey-9">
      Поиск компаний, продукции и услуг
    </h3>
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
      <div class="col-2" style="padding-left: 10px">
        <SelectOkved v-model="selectedOKVED" outlined />
      </div>
    </div>
    <div class="search-result" style="padding: 10px">
      <div v-if="resultSearch.companies.length" class="row">
        <div class="col-12">
          <p class="text-h5">Компании</p>
        </div>
        <div class="col-12">
          <div class="row" style="gap: 10px">
            <CounterpartyElement
              v-for="(counterparty, index) in resultSearch.companies"
              :key="index"
              :counterparty="counterparty"
              @on-open-map="openMapDialog"
            />
          </div>
        </div>
      </div>
      <div v-if="resultSearch.goods.length" class="row">
        <div class="col-12">
          <p class="text-h5">Товары</p>
        </div>
        <div class="col-12">
          <div class="row" style="gap: 10px">
            <GoodElement
              v-for="(good, index) in resultSearch.goods"
              :key="index"
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
          <div class="row" style="gap: 10px">
            <div v-for="(service, index) in resultSearch.services" :key="index">
              <q-chip
                icon="home_repair_service"
                color="deep-orange"
                text-color="white"
                >{{ service.name }}</q-chip
              >
            </div>
          </div>
        </div>
      </div>
      <div v-if="isEmpty">
        <p class="text-h5">Запрос не дал результатов</p>
      </div>
    </div>
  </div>
  <MapDialog
    v-model="mapDialog"
    v-model:coordinate="coordinate"
    :successButton="false"
    :resetButton="false"
  />
</template>

<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Components
   */
  import SelectOkved from 'Components/SelectOkved.vue';
  import GoodElement from '../components/good/GoodElement.vue';
  import CounterpartyElement from '../components/counterparty/CounterpartyElement.vue';
  import MapDialog from '../components/counterparty/MapDialog.vue';

  /**
   * Hooks
   */
  import { useUserSearchPageGuard } from 'Src/hooks';

  /**
   * Types
   */
  import { AllEntity, apiGlobalSearch } from '../api/search';

  useUserSearchPageGuard();

  const search = ref('');

  const loading = ref(false);

  const isEmpty = ref(false);

  const mapDialog = ref(false);

  const coordinate = ref<Coordinate>({ lat: 55.751244, lon: 37.618423 });

  const selectedOKVED = ref({
    code: '',
    name: '',
    additional_info: '',
  });

  const resultSearch = ref<AllEntity>({
    companies: [],
    goods: [],
    services: [],
  });

  async function startSearch() {
    if (!search.value) {
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

  function openMapDialog(value: Counterparty) {
    mapDialog.value = true;

    coordinate.value = {
      lat: value.latitude || 55.751244,
      lon: value.longitude || 37.618423,
      title: value.name,
    };
  }
</script>

<style scoped>
  .search-result > .row:nth-child(n + 2) {
    margin-top: 10px;
  }
</style>
