<template>
  <div class="q-pa-md">
    <q-card class="my-card absolute-center" style="width: 400px; padding: 20px">
      <q-card-section>
        <q-input
          v-model="userData.name"
          :rules="[ requiredStringRule ]"
          label="Login"
        />
        <q-input
          v-model="userData.email"
          :rules="[ requiredStringRule ]"
          label="Email"
        />
        <q-input
          v-model="userData.password"
          :rules="[ requiredStringRule ]"
          :type="isPwd ? 'password' : 'text'"
          label="Password"
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
          :rules="[ requiredStringRule ]"
          :type="isPwd ? 'password' : 'text'"
          label="Confirm password"
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
          :disable="disabled()"
          style="margin-top: 20px"
          @click="registration()"
        />
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import AuthService from '../services/auth.service';
  import { requiredStringRule } from '../common/rules';

  const router = useRouter();

  const userData = ref({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
  });

  let isPwd = ref(true);

  async function registration() {
      await AuthService.registration({
        name: userData.value.name,
        email: userData.value.email,
        password: userData.value.password
      });
      router.push('/login');
  }
  function disabled(): boolean
  {
    return !userData.value.name
      || !userData.value.email
      || !userData.value.password
      || !userData.value.confirmPassword
      || userData.value.password !== userData.value.confirmPassword;
  }
</script>
