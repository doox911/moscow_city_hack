<template>
  <q-page>
    <div class='q-pa-md'>
      <div class='row justify-center'>
        <div class='col-12 col-md-1'></div>
        <div class='col-12 col-md-2' style='margin-bottom: 10px;'>
          <q-item clickable to='/admin/profile'>
            <q-item-section>
              Мой профиль
            </q-item-section>
          </q-item>
          <q-list dense bordered padding class='rounded-borders'>
            <q-item clickable to='/admin/profile/request'>
              <q-item-section>
                Заявки
              </q-item-section>
            </q-item>
            <q-item clickable to='/admin/profile/edit'>
              <q-item-section>
                Настройки профиля
              </q-item-section>
            </q-item>
            <q-item clickable to='/admin/profile/owner'>
              <q-item-section>
                Добавление организации
              </q-item-section>
            </q-item>
            <q-item clickable to='/admin/profile/registration'>
              <q-item-section>
                Регистрация пользователя
              </q-item-section>
            </q-item>
          </q-list>
        </div>
        <div class='col-12 col-md-7'>
          <q-page-container>
            <router-view />
          </q-page-container>
        </div>
        <div class='col-12 col-md-1'></div>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">

  /**
   * Routers
   */
  import { useRoute, useRouter } from 'vue-router';
  /**
   * Hooks
   */
  import { useUserProfilePageGuard } from 'Src/hooks';

  useUserProfilePageGuard();

</script>
