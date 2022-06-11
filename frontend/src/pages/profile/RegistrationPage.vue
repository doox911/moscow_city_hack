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
          v-model="userData.secondName"
          :rules="[ requiredStringRule ]"
          label="Фамилия"
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
  import { useRoute, useRouter } from 'vue-router';
  import AuthService from '../../services/auth.service';
  import { requiredStringRule, requiredPasswordRule, requiredSelectRule } from '../../common/rules';
  import { Roles } from '../../constants';
  import { userStore } from '../../stores/userStore';

  const { user } = userStore();
  const router = useRouter();
  const route = useRoute();

  /* if(user.role !== Roles.Admin) router.push(user.role); */

  const userData = ref({
    name: '',
    secondName: '',
    email: '',
    role: '',
    password: '',
    confirmPassword: '',
  });

  let isPwd = ref(true);

  const roleList = ref([
    { value: Roles.Admin, label: 'Администратор' },
    { value: Roles.Government, label: 'Правительственный персонал' },
    { value: Roles.Owner, label: 'Собственник компании' },
  ])

  async function registration() {
    const response = await AuthService.registration({
      name: userData.value.name,
      second_name: userData.value.secondName,
      email: userData.value.email,
      role: userData.value.role.value,
      password: userData.value.password
    });
    if(response) router.push({ name: 'home' });
  }
  /**
   * Отправить запрос можно только при наличии всех значений
   */
  const disabled = computed(() => {
    return !userData.value.name
      || !userData.value.secondName
      || !userData.value.email
      || !userData.value.role
      || !userData.value.password
      || !userData.value.confirmPassword
      || userData.value.password !== userData.value.confirmPassword;
  })
</script>
