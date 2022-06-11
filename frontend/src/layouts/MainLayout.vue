<template>
  <q-layout view="hHh Lpr lff" class="shadow-2 rounded-borders">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat @click="drawer = !drawer" round dense icon="menu" />

        <div class="non-selectable ">
          <span class="text-weight-bold text-green">М</span>ос<span class="text-weight-bold text-green">И</span>порт<span class="text-weight-bold text-green">М</span>ониторинг
        </div>

        <q-space />

        <div>{{ getUserInfo }}</div>

        <q-btn flat @click="logout" round dense icon="logout">
          <Tooltip v-model="tooltip" text="Выход" />
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="drawer" :width="200" :breakpoint="500" overlay bordered class="bg-grey-3">
       <q-scroll-area class="fit">
          <q-list>

            <template v-for="(menuItem, index) in menuList" :key="index">
              <q-item>
                <q-item-section avatar>
                  <q-icon :name="menuItem.iconName" />
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
  import { computed, onMounted, ref } from 'vue'
  import { storeToRefs } from 'pinia'

  /**
   * Api
   */
  import { apiMenuList } from '../api/menu';
  /**
   * Common
   */
  import { capitalizeFirstLetter } from 'Src/common'

  /**
   * Components
   */
  import Tooltip from 'Components/common/Tooltip.vue';

  /**
   * Routers
   */
  import { useRouter } from 'vue-router';

  /**
   * Store
   */
  import { userStore, menuStore } from '../stores';

  /**
   * Services
   */
  import AuthService from '../services/auth.service';

  const router = useRouter();

  const { user } = storeToRefs(userStore());

  const drawer = ref(false);

  const tooltip = ref(false);

  const getUserInfo = computed(() => {
    const { name, second_name, patronymic } = user.value;

    const n = name?.length
      ? `${capitalizeFirstLetter(name)}`
      : ''

    const sn = second_name?.length
      ? `${capitalizeFirstLetter(second_name[0])}.`
      : ''

    const p = patronymic?.length
      ? `${capitalizeFirstLetter(patronymic[0])}.`
      : ''

    return `${n} ${sn} ${p}`;
     
  })

  async function logout() {
    await AuthService.logout();

    router.push('/login');
  }

  const { setMenu } = menuStore();

  const { menuList } = storeToRefs(menuStore());


  async function loadMenu()
  {
    const list = await apiMenuList();
    console.log(list)
    setMenu(list);
  }

  onMounted(() => {
    loadMenu();

  });
</script>
