<template>
  <q-page class="q-mx-md">
    <div class="row" style="margin-bottom:10px;">
      <div class="row">
        <div class="q-px-sm non-selectable text-weight-regular text-grey-9">
          <p class="text-h5">Информация о компании</p>
          <q-separator />
          <p class="q-my-xs"><b class="q-pr-sm">Название:</b>{{ counterparty.name }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">Полное название:</b>{{ counterparty.full_name }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">ИНН:</b>{{ counterparty.inn }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">ОГРН:</b>{{ counterparty.ogrn }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">Адрес:</b>{{ counterparty.address }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">Почта:</b>{{ counterparty.email }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">телефон:</b>{{ counterparty.phone }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">сайт:</b>{{ counterparty.site }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">создано:</b>{{ counterparty_created }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">обновлено:</b>{{ counterparty_updated }}</p>
        </div>
      </div>
    </div>
    
    <q-separator />

    <div class="row">
      <div v-for="service in servicesRef">
        <q-chip icon="home_repair_service" color="deep-orange" text-color="white">{{ service.name }}</q-chip>
      </div>
    </div>
    <q-separator />

    <div class="row" style="margin-bottom:10px;">
      <div class="row">
        <div class="q-px-sm non-selectable text-weight-regular text-grey-9">
          <p class="text-h5">Товары</p>
          <q-separator />
          <div class="row" style = "gap:10px; padding:10px;">
            <div v-for="good in goodsRef" class = "good-block">
              <div class = "icon">
                <q-icon name="photo_library" class="cursor-pointer"/>
              </div>
              <div class = "title">Название: </div>
              <div>{{ good.name }}</div>
              <div class = "title">Брэнд: </div>
              <div>{{ good.brand }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <q-separator />
  </q-page>
</template>

<script setup lang="ts">
  import { ref, watch, computed, onMounted} from 'vue';
  
  /**
   * Api
   */
  import { apiCounterparty, Counterparty as CounterpartyType } from 'Src/api/counterparty';
  
  /**
   * Common
   */
  import { getDefaultCounterparty, setDateAndTimeToDateTimeComponent } from 'Src/common';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from 'Src/stores';

  /**
   * Types
   */
  import { ImportSortColoumn } from '../types';
  import { Service } from 'bonjour-service';
  import { Good } from '../../api/good';


  const { user } = storeToRefs(userStore());

  const dialog = ref(false);

  const counterparty = ref<CounterpartyType>(user.value.company || getDefaultCounterparty());

  const selectCounterparty = ref<CounterpartyType>(getDefaultCounterparty());

  const counterparty_created = computed(() => setDateAndTimeToDateTimeComponent(counterparty.value.created_at));

  const counterparty_updated = computed(() => setDateAndTimeToDateTimeComponent(counterparty.value.updated_at));

  const goodsRef = ref<Good[]>([]);
  const servicesRef = ref<Service[]>([]);

  function openEditDialog() {
    dialog.value = true;
    selectCounterparty.value = counterparty.value;
  }

  async function onRequestGoods({ page, size, columns, searchText }: { page: number, size: number, columns: ImportSortColoumn, searchText: string })
  {
    if(user.value.company?.id)
    {
      const { goods, services } = await apiCounterparty(user.value.company?.id);

      console.log(goods)
      console.log(services)
      goodsRef.value = goods;
      servicesRef.value = services;
    }
  }

  onMounted(async () => {
    await onRequestGoods({
      page: 1,
      size: 10,
      columns: {},
      searchText: ''
    });
  })
</script>

<style scoped lang="scss">
  .good-block
  {
    min-width: 200px;
    padding: 12px;
    color: #000;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 5px #0003, 0 2px 2px #00000024, 0 3px 1px -2px #0000001f;

    .title
    {
      opacity: .54;
      font-weight: 500;
      font-size: 12px;
    }
    .icon
    {
      font-size: 6em;
      color: gray;
      text-align: center;
    }
  }
</style>
