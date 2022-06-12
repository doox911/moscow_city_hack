<template>
  <div class="row" style = "justify-content: center;">
    <div class="col-12" style="text-align:center;">
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
  <UserDialog 
    v-if="user.role === Roles.Admin"
    v-model="dialog"
    v-model:user="u"
    :loading="loading"
    :use-reset="false"
    @on-success="onSuccess"
  />
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';
  import AuthService from 'src/services/auth.service';

  /**
   * Common
   */
  import { getRoleDescription, setDateAndTimeToDateTimeComponent } from 'Src/common';

  /**
   * Components
   */
  import UserDialog from './UserDialog.vue';

  /**
   * Constants
   */
  import { Roles } from 'Src/constants';
  
  /**
   * Store
   */
  import { storeToRefs } from 'pinia'
  import { userStore } from 'Src/stores';

  /**
   * Types
   */  
  import type { User } from 'Src/stores';

  const { user } = storeToRefs(userStore());

  const user_role = computed(() => getRoleDescription(user.value.role));

  const user_created = computed(() => setDateAndTimeToDateTimeComponent(user.value.created_at));

  const user_updated = computed(() => setDateAndTimeToDateTimeComponent(user.value.updated_at));

  const dialog = ref(false);

  const loading = ref(false);

  const u = ref<User>({...user.value});
  
  watch(user, (_u: User) => {
    u.value = { ..._u };
  });

  async function onSuccess() {
    loading.value = true;

    await AuthService.updateUserInfo();

    loading.value = false;
  }
</script>

<style lang="scss" scoped>
  .app-avatar {
    border-radius: 50%;
  }
</style>
