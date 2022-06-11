<template>
  <DialogCommonWrapper 
    v-model="dialog" 
    button-text="Выбрать компанию"
    header-text="Выбрать компанию"
    @on-reset="onReset"
  >
    <div class="col">
      <div class="row">
        <div class="col">
          <q-input v-model="filter" :loading="loading" placeholder="Фильтр по компаниям">
            <template v-slot:append>
              <q-icon name="search" @click="getCompany" class="cursor-pointer"/>
            </template>
          </q-input>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <q-select 
            v-model="selected"
            :loading="loading"
            :options="counterparties"
            label="Выбрать компанию"
            option-label="name"
          />
        </div>
      </div>
    </div>
  </DialogCommonWrapper>
</template>

<script setup lang="ts">
  import { computed, onMounted, ref} from 'vue';

  /**
   * Api
   */
  import { apiCounterparties, Counterparty } from 'Src/api/counterparty';

  /**
   * Common
   */
  import DialogCommonWrapper from 'Components/common/dialogs/DialogCommonWrapper.vue'

  const emit = defineEmits(['update:modelValue']);

  const props = withDefaults(defineProps<{
    modelValue?: Counterparty;
  }>(), {
    modelValue: undefined,
  });

  const page = ref(1);

  const item_per_page = ref(15);

  const dialog = ref(false);

  const loading = ref(false);

  const filter = ref('');

  const counterparties = ref<Counterparty[]>([]);

  const selected = computed({
    get() {
      return props.modelValue;
    },
    set(v?: Counterparty | undefined) {
      emit('update:modelValue', v);
    }
  })

  const onReset = () => {
    filter.value  = '';
  };

  async function updateCounterparty(s = '') {
    const pagination = await apiCounterparties({
      params: {
        page: page.value,
        item_per_page: item_per_page.value,
        filters: {
          search_string: s,
          columns: {
            name: 'asc',
            inn: 'desc'
          }
        }
      }
    });

    counterparties.value = pagination.counterparties.data.map(e => {
      return {
        ...e,
        name: e.name.length > 50 
          ? e.name.slice(0, 50) + '...'
          : e.name,
      };
    })
  }

  async function getCompany() {
    loading.value = true;

    await updateCounterparty(filter.value);

    loading.value = false;

    selected.value = undefined;
  }

  onMounted(async () => {
    loading.value = true;
    
    await updateCounterparty();

    loading.value = false;
  });

</script>

<style lang="scss" scoped>
</style>
