<template>
  <div class="counterparty-block">
    <div
      v-if="counterparty.base64_photos && counterparty.base64_photos.length"
      class="row"
      style="justify-content: center"
    >
      <div class="col-12">
        <q-carousel
          v-model="slide"
          height="160px"
          animated
          arrows
          navigation
          infinite
        >
          <q-carousel-slide
            v-for="(encodedImage, index) of counterparty.base64_photos"
            :key="index"
            :name="index + 1"
          >
            <div
              class="row fit justify-start items-center q-gutter-xs q-col-gutter no-wrap"
            >
              <q-img
                class="rounded-borders col-12 full-height"
                :src="`data:image/png;base64,${encodedImage}`"
              />
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
    <div v-else class="icon">
      <q-icon name="photo_library" class="cursor-pointer" />
    </div>
    <div class="content">
      <div class="title">Название:</div>
      <div>{{ counterparty.name }}</div>
      <div class="title">ИНН:</div>
      <div>{{ counterparty.inn }}</div>
      <div class="title">ОГРН:</div>
      <div>{{ counterparty.ogrn }}</div>
      <div class="title">Почта:</div>
      <div>{{ counterparty.email }}</div>
      <div class="title">телефон:</div>
      <div>{{ counterparty.phone }}</div>
      <div class="title">сайт:</div>
      <div>{{ counterparty.site }}</div>
    </div>
  </div>
</template>
<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Api
   */
  import { Counterparty } from 'Src/api/counterparty';

  withDefaults(
    defineProps<{
      counterparty: Counterparty;
      modelValue?: boolean;
    }>(),
    {
      modelValue: false,
    },
  );

  const slide = ref(1);
</script>

<style scoped lang="scss">
  .counterparty-block {
    min-width: 220px;
    color: #000;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 5px #0003, 0 2px 2px #00000024, 0 3px 1px -2px #0000001f;

    .content {
      padding: 10px;
      .title {
        opacity: 0.54;
        font-weight: 500;
        font-size: 12px;
      }
    }
    .icon {
      font-size: 6em;
      color: gray;
      text-align: center;
      padding: 10px;
    }
  }
</style>
