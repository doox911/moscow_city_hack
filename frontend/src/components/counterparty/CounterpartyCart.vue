<template>
  <div class="row" style="margin-bottom: 10px">
    <div class="row col-12">
      <div
        class="q-px-sm non-selectable text-weight-regular text-grey-9 col-12"
      >
        <div
          v-if="photos && photos.length"
          class="row"
          style="justify-content: center"
        >
          <div class="col-12 col-md-6">
            <q-carousel
              v-model="slide"
              height="400px"
              animated
              arrows
              navigation
              infinite
            >
              <q-carousel-slide
                v-for="(encodedImage, index) of photos"
                :key="index"
                :name="index + 1"
              >
                <div
                  class="row fit justify-start items-center q-gutter-xs q-col-gutter no-wrap"
                >
                  <q-img
                    class="rounded-borders col-12 full-height"
                    :src="`${encodedImage}`"
                  />
                </div>
              </q-carousel-slide>
            </q-carousel>
          </div>
        </div>
        <p class="text-h5">Информация о компании</p>
        <q-separator />
        <p class="q-my-xs">
          <b class="q-pr-sm">Название:</b>{{ counterparty.name }}
        </p>
        <p class="q-my-xs">
          <b class="q-pr-sm">Полное название:</b>{{ counterparty.full_name }}
        </p>
        <p class="q-my-xs"><b class="q-pr-sm">ИНН:</b>{{ counterparty.inn }}</p>
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
      </div>
    </div>
  </div>

  <div class="row" style="margin-bottom: 10px">
    <div class="row col-12">
      <div
        class="q-px-sm non-selectable text-weight-regular text-grey-9 col-12"
      >
        <p class="text-h5">Услуги</p>
        <q-separator />
        <div class="row" style="padding-top: 10px">
          <div v-for="(service, index) in servicesRef" :key="index">
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
  </div>

  <div class="row" style="margin-bottom: 10px">
    <div class="row col-12">
      <div
        class="q-px-sm non-selectable text-weight-regular text-grey-9 col-12"
      >
        <p class="text-h5">Товары</p>
        <q-separator />
        <div class="row" style="gap: 10px; padding-top: 10px">
          <GoodElement
            v-for="(good, index) in goodsRef"
            :good="good"
            :key="index"
          />
        </div>
      </div>
    </div>
  </div>
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
   * Store
   */
  import { storeToRefs } from 'pinia';
  import { userStore } from 'Src/stores';

  /**
   * Types
   */
  import type { ImportSortColumn } from 'Src/types';
  import type { Good } from 'Src/api/good';
  import type { Service } from 'Src/api/service';

  /**
   * Components
   */
  import GoodElement from 'Components/good/GoodElement.vue';

  const { user } = storeToRefs(userStore());

  const counterparty = ref<CounterpartyType>(
    user.value.company || getDefaultCounterparty(),
  );

  const counterparty_created = computed(() =>
    setDateAndTimeToDateTimeComponent(counterparty.value.created_at),
  );

  const counterparty_updated = computed(() =>
    setDateAndTimeToDateTimeComponent(counterparty.value.updated_at),
  );

  const slide = ref(1);

  const photos = ref<any>([]);

  const goodsRef = ref<Good[]>([]);
  const servicesRef = ref<Service[]>([]);

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
      const { goods, services, base64_photos } = await apiCounterparty(
        user.value.company?.id,
      );

      goodsRef.value = goods as Good[];
      servicesRef.value = services as Service[];

      photos.value = base64_photos;
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
