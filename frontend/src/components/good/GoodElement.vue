<template>
  <div class="good-block">
    <div
      v-if="good.base64_photos && good.base64_photos.length"
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
            v-for="(encodedImage, index) of good.base64_photos"
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
    <div v-else class="icon">
      <q-icon name="photo_library" class="cursor-pointer" />
    </div>
    <div class="content">
      <div class="title">Название:</div>
      <div>{{ good.name }}</div>
      <template v-if="good.brand">
        <div class="title">Брэнд:</div>
        <div>{{ good.brand }}</div>
      </template>
    </div>
  </div>
</template>
<script setup lang="ts">
  import { ref } from 'vue';

  /**
   * Api
   */
  import { Good } from 'Src/api/good';

  withDefaults(
    defineProps<{
      good: Good;
      modelValue?: boolean;
    }>(),
    {
      modelValue: false,
    },
  );

  const slide = ref(1);
</script>

<style scoped lang="scss">
  .good-block {
    width: 250px;
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
