<template>
  <q-page class="row items-center justify-center">
    <div class="col-auto">

        <h1 class="text-center non-selectable">Добро пожаловать</h1>

<div>
<q-card class="my-card" style="width: 400px; padding: 20px">
          <q-card-section>
            <q-input
              v-model="userData.email"
              :rules="[ requiredStringRule ]"
              label="Адрес электронной почты"
            />
            <q-input
              v-model="userData.password"
              :type="isPwd ? 'password' : 'text'"
              :rules="[ requiredStringRule ]"
              label="Пароль"
            >
              <template v-slot:append>
                <q-icon
                  :name="isPwd ? 'visibility_off' : 'visibility'"
                  class="cursor-pointer"
                  @click="isPwd = !isPwd"
                />
              </template>
            </q-input>
            <q-btn
              color="primary float-right"
              label="Вход"
              :disable="disabled"
              style="margin-top: 20px"
              @click="login"
            />
          </q-card-section>
        </q-card>
</div>
        
      </div>
  </q-page>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';
  import { useRouter } from 'vue-router';

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

  let isPwd = ref(true);

  async function login() {
    await AuthService.login({
      email: userData.value.email,
      password: userData.value.password,
    });

    const { user } = userStore();

    router.push(user.role);
  }

  const disabled = computed(() => {
    return !userData.value.email || !userData.value.password;
  })

</script>
