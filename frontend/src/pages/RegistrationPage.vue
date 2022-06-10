<template>
  <div class="q-pa-md">
    <q-card class="my-card absolute-center" style="width: 400px; padding: 20px">
      <q-card-section>
        <q-input
          v-model="userData.name"
          :rules="[ requiredStringRule ]"
          label="Имя"
        />
        <q-input
          v-model="userData.email"
          :rules="[ requiredStringRule ]"
          label="Email"
        />
        <q-select
          v-model="userData.role"
          :options="roleList"
          :rules="[ requiredSelectRule ]"
          label="Роль"
        />
        <q-input
          v-model="userData.password"
          :rules="[ requiredStringRule ]"
          :type="isPwd ? 'password' : 'text'"
          label="Пароль"
          ref="fldPasswordChange"
        >
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility_off' : 'visibility'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
        </q-input>
        <q-input
          v-model="userData.confirmPassword"
          :rules="[ requiredStringRule, (v) => requiredPasswordRule(v, userData.password) ]"
          :type="isPwd ? 'password' : 'text'"
          label="Подтверждение пароля"
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
          label="Registration"
          :disable="disabled"
          style="margin-top: 20px"
          @click="registration()"
        />
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';
  import { useRouter } from 'vue-router';
  import AuthService from '../services/auth.service';
  import { requiredStringRule, requiredPasswordRule, requiredSelectRule } from '../common/rules';

  const router = useRouter();

  const userData = ref({
    name: '',
    email: '',
    role: '',
    password: '',
    confirmPassword: '',
  });

  let isPwd = ref(true);

  const roleList = ref([
    { value: 'admin', label: 'Администратор' },
    { value: 'user', label: 'Пользователь' },
    { value: 'company', label: 'Предприятие' },
  ])

  async function registration() {
      await AuthService.registration({
        name: userData.value.name,
        email: userData.value.email,
        role: userData.value.role,
        password: userData.value.password
      });
      router.push('/login');
  }
  /**
   * Отправить запрос можно только при наличии всех значений
   */
  const disabled = computed(() => {
    return !userData.value.name
      || !userData.value.email
      || !userData.value.password
      || !userData.value.confirmPassword
      || userData.value.password !== userData.value.confirmPassword;
  })
</script>
