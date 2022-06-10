<template>
  <q-page>
    <div class="row items-center justify-end">
      <q-btn class="q-mt-sm q-mr-sm" flat round color="primary" icon="info">
        <Tooltip v-model="infoTooltip" text="Информация о программе" />
      </q-btn>
    </div>
    <div class="row items-center justify-center">
      <div class="col-auto">
        <h1
          class="text-center non-selectable text-weight-regular text-h5 text-grey-9"
        >
          <span class="text-weight-bold">Д</span>обро пожаловать в <span class="text-weight-bold text-green">М</span>ос<span class="text-weight-bold text-green">И</span>порт<span class="text-weight-bold text-green">З</span>ам
        </h1>
      </div>
    </div>
    <div class="row items-center justify-center">
      <q-card class="my-card" style="width: 400px; padding: 20px">
        <q-form @submit="login" @reset="reset">
          <q-card-section class="q-ma-none q-pa-none">
            <p
              class="q-ma-none q-pa-none text-center non-selectable text-weight-regular text-grey-9"
            >
              Вход в систему
            </p>
          </q-card-section>
          <q-card-section class="q-mb-none q-pb-none">
            <q-input
              v-model="userData.email"
              :rules="[requiredStringRule]"
              label="Адрес электронной почты"
              :disable="loading"
            />
            <q-input
              v-model="userData.password"
              :type="isPwd ? 'password' : 'text'"
              :rules="[requiredStringRule]"
              label="Пароль"
              :disable="loading"
            >
              <template v-slot:append>
                <q-icon
                  :name="isPwd ? 'visibility_off' : 'visibility'"
                  class="cursor-pointer"
                  @click="isPwd = !isPwd"
                />
              </template>
            </q-input>
          </q-card-section>
          <q-card-actions class="q-my-none q-py-none" align="right">
            <q-btn
              color="primary float-right"
              label="Сбросить"
              style="margin-top: 20px"
              type="reset"
              :loading="loading"
            >
              <Tooltip v-model="resetTooltip" text="Сбросить форму" />
            </q-btn>
            <q-btn
              color="primary float-right"
              label="Вход"
              :disable="disabled"
              style="margin-top: 20px"
              type="submit"
              :loading="loading"
            >
              <Tooltip v-model="loginTooltip" text="Вход в программу" />
            </q-btn>
          </q-card-actions>
        </q-form>
      </q-card>
    </div>
  </q-page>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';
  import { useRouter } from 'vue-router';

  /**
   * Components
   */
  import Tooltip from 'Components/common/Tooltip.vue';

  /**
   * Rules
   */
  import { requiredStringRule } from '../common/rules';

  /**
   * Services
   */
  import AuthService from '../services/auth.service';

  /**
   * Store
   */
  import { userStore } from '../stores/userStore';

  const router = useRouter();

  const userData = ref({
    email: '',
    password: '',
  });

  const disabled = computed(() => {
    return !userData.value.email?.length || !userData.value.password?.length;
  });

  const loading = ref(false);

  const isPwd = ref(true);

  const infoTooltip = ref(false);

  const loginTooltip = ref(false);

  const resetTooltip = ref(false);

  async function login() {
    if (!disabled.value) {
      loading.value = true;

      await AuthService.login({
        email: userData.value.email,
        password: userData.value.password,
      });

      loading.value = false;

      const { user } = userStore();

      if (user.id !== -1) {
        router.push("/" + user.role);
      }
    }
  }

  function reset() {
    userData.value = {
      email: '',
      password: '',
    };
  }
</script>
