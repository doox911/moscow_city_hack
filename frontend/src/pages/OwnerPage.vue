<template>
  <q-page class="q-mx-md">
    <div class="row" style="margin-bottom: 10px">
      <div class="row">
        <div class="q-px-sm non-selectable text-weight-regular text-grey-9">
          <p class="text-h5">Информация о компании</p>
          <q-separator />
          <p class="q-my-xs">
            <b class="q-pr-sm">Название:</b>{{ counterparty.name }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">Полное название:</b>{{ counterparty.full_name }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">ИНН:</b>{{ counterparty.inn }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">ОГРН:</b>{{ counterparty.ogrn }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">Адрес:</b>{{ counterparty.address }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">Почта:</b>{{ counterparty.email }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">телефон:</b>{{ counterparty.phone }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">сайт:</b>{{ counterparty.site }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">создано:</b>{{ counterparty_created }}
          </p>
          <p class="q-my-xs">
            <b class="q-pr-sm">обновлено:</b>{{ counterparty_updated }}
          </p>

          <q-btn
            color="primary float-right"
            label="Изменить"
            style="margin-top: 10px"
            type="reset"
            @click="openEditDialog"
          />
        </div>
      </div>
    </div>

    <q-separator />

    <GoodsTable
      :isAttach="false"
      :isSearch="false"
      :good="goodsRef.data"
      :loading="goodsRef.loading"
      :rowsNumber="goodsRef.rowsNumber"
    />
  </q-page>
  <CounterpartyDialog
    v-model="dialog"
    v-model:counterparty="selectCounterparty"
  />
</template>

<script setup lang="ts">
  import { ref, computed, onMounted } from 'vue';

  /**
   * Api
   */
  import {
    apiCounterparty,
    Counterparty as CounterpartyType,
  } from 'Src/api/counterparty';

  /**
   * Common
   */
  import {
    getDefaultCounterparty,
    setDateAndTimeToDateTimeComponent,
  } from 'Src/common';

  /**
   * Components
   */
  import CounterpartyDialog from 'Components/counterparty/CounterpartyDialog.vue';
  import GoodsTable from 'Components/tables/GoodsTable.vue';

  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia';
  import { userStore } from 'Src/stores';

  /**
   * Types
   */
  import { ImportSortColumn } from '../types';

  useUserPageGuard();

  const { user } = storeToRefs(userStore());

  const dialog = ref(false);

  const counterparty = ref<CounterpartyType>(
    user.value.company || getDefaultCounterparty(),
  );

  const selectCounterparty = ref<CounterpartyType>(getDefaultCounterparty());

  const counterparty_created = computed(() =>
    setDateAndTimeToDateTimeComponent(counterparty.value.created_at),
  );

  const counterparty_updated = computed(() =>
    setDateAndTimeToDateTimeComponent(counterparty.value.updated_at),
  );

  const goodsRef: any = ref({
    data: [],
    rowsNumber: 0,
    loading: false,
  });

  function openEditDialog() {
    dialog.value = true;
    selectCounterparty.value = counterparty.value;
  }

  async function onRequestGoods({
    page,
    size,
    columns,
    searchText,
  }: {
    page: number;
    size: number;
    columns: ImportSortColumn;
    searchText: string;
  }) {
    if (user.value.company?.id) {
      goodsRef.value.loading = true;
      const { goods } = await apiCounterparty(user.value.company?.id);
      goodsRef.value.rowsNumber = goods?.length;
      goodsRef.value.data = goods;
      goodsRef.value.loading = false;
    }
  }

  onMounted(async () => {
    await onRequestGoods({
      page: 1,
      size: 10,
      columns: {},
      searchText: '',
    });
  });
</script>
