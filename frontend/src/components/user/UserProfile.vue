<template>
  <div class="row">
    <div class="q-mr-sm">
      <img class="app-avatar" src="~/assets/avatar.png" alt="Аватар">
    </div>
    <div class="q-px-sm non-selectable text-weight-regular text-grey-9">
      <p class="text-h5">Информация о пользователе</p>
      <q-separator />
      <p class="q-my-xs"><b class="q-pr-sm">Идентификатор:</b>{{ user.id }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Имя:</b>{{ user.name }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Фамилия:</b>{{ user.second_name }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Отчество:</b>{{ user.patronymic }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Электронный адрес:</b>{{ user.email }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Роль:</b>{{ user_role }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Создан:</b>{{ user_created }}</p>
      <p class="q-my-xs"><b class="q-pr-sm">Обновлён:</b>{{ user_updated }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  /**
   * Common
   */
  import { getRoleDescription, setDateAndTimeToDateTimeComponent } from 'Src/common';

  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from 'Src/stores';

  const { user } = storeToRefs(userStore());

  const user_role = computed(() => getRoleDescription(user.value.role));

  const user_created = computed(() => setDateAndTimeToDateTimeComponent(user.value.created_at));

  const user_updated = computed(() => setDateAndTimeToDateTimeComponent(user.value.updated_at));

</script>

<style lang="scss" scoped>
  .app-avatar {
    border-radius: 50%;
  }
</style>
