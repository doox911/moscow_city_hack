<template>
  <q-layout view="hHh Lpr lff" class="rounded-borders">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat @click="drawer = !drawer" round dense icon="menu" />

        <div class="non-selectable ">
          <span class="text-weight-bold text-green">М</span>ос<span
            class="text-weight-bold text-green">И</span>порт<span class="text-weight-bold text-green">М</span>ониторинг
        </div>

        <q-space />

        <q-btn flat @click="goToProfile">
          {{ getUserInfo }}
          <Tooltip v-model="profileTooltip" text="Просмотр профиля" />
        </q-btn>

        <q-btn flat @click="logout" round dense icon="logout">
          <Tooltip v-model="tooltip" text="Выход" />
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="drawer" :width="200" :breakpoint="500" overlay bordered class="bg-grey-3">
      <q-scroll-area class="fit">
        <q-list>

          <template v-for="(menuItem, index) in menuList" :key="index">
            <q-item 
              :active="menuItem.to === route.path"
              :clickable="menuItem.to !== route.path" 
              class="non-selectable" 
              @click="router.push(menuItem.to)"
            >
              <q-item-section avatar>
                <q-icon :name="menuItem.icon_name" />
              </q-item-section>
              <q-item-section>
                {{ menuItem.name }}
              </q-item-section>
            </q-item>
            <!-- <q-separator :key="'sep' + index"  v-if="menuItem.separator" /> -->
          </template>

        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue'
  import { storeToRefs } from 'pinia'

  /**
   * Api
   */
  import { apiMenuList } from '../api/menu';

  /**
   * Common
   */
  import { getUserName } from 'Src/common';

  /**
   * Components
   */
  import Tooltip from 'Components/common/Tooltip.vue';

  /**
   * Routers
   */
  import { useRoute, useRouter } from 'vue-router';

  /**
   * Store
   */
  import { userStore, menuStore } from '../stores';

  /**
   * Services
   */
  import AuthService from '../services/auth.service';

  const route = useRoute();

  const router = useRouter();

  const { user } = storeToRefs(userStore());

  const drawer = ref(false);

  const tooltip = ref(false);

  const profileTooltip = ref(false);

  const getUserInfo = computed(() => getUserName(user.value));

  async function logout() {
    await AuthService.logout();

    router.push('/login');
  }

  function goToProfile() {
    router.push(`/${user.value.role}/profile`);
  }

  const { menuList } = storeToRefs(menuStore());

  async function getMenuList()
  {
    const { setMenu } = menuStore();
    setMenu(await apiMenuList());

    const { loadAllUser } = userStore();
    await loadAllUser();
  }
  getMenuList();
</script>
