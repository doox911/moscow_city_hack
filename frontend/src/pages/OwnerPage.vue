<template>
  <q-page class="q-mx-md">
    <div class="row">
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
          <p class="q-my-xs"><b class="q-pr-sm">создано:</b>{{ counterparty.created_at }}</p>
          <p class="q-my-xs"><b class="q-pr-sm">обновлено:</b>{{ counterparty.updated_at }}</p>

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

    <div class="row">
      <h3>Продукция</h3>
    </div>

    <q-separator />

  </q-page>
  <CounterpartyDialog 
    v-model="dialog" 
    v-model:counterparty="selectCounterparty"
  />
</template>

<script setup lang="ts">
  import { ref, watch, computed} from 'vue';
  
  /**
   * Api
   */
  import { apiCounterparty, Counterparty } from 'Src/api/counterparty';
  
  /**
   * Common
   */
  import { getDefaultCounterparty } from 'Src/common';

  /**
   * Components
   */
  import CounterpartyDialog from 'Components/counterparty/CounterpartyDialog.vue';

  /**
   * Hooks
   */
  import { useUserPageGuard } from 'Src/hooks';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from 'Src/stores';

  useUserPageGuard();

  const { user } = storeToRefs(userStore());

  const dialog = ref(false);

  const counterparty = ref<Counterparty>(getDefaultCounterparty());

  const selectCounterparty = ref<Counterparty|undefined>();

  watch(user, async (u) => {
    if (u.company) {
      const response = await apiCounterparty(u.company.id);

      if(response) {
        counterparty.value = response;
      }
    }
  });
  
  function openEditDialog() {
    dialog.value = true;
    selectCounterparty.value = counterparty.value;
  }

  if(user.value.company)
    load(user.value.company);

</script>
