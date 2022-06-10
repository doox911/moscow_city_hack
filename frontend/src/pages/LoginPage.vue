<template>
  <div class="q-pa-md">
    <q-card class="my-card absolute-center" style="width: 400px; padding: 20px">
      <q-card-section>
        <q-input
          v-model="userData.email"
          :rules="[ requiredStringRule ]"
          label="Email"
        />
        <q-input
          v-model="userData.password"
          :type="isPwd ? 'password' : 'text'"
          :rules="[ requiredStringRule ]"
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
        <q-btn
          color="primary float-right"
          label="Login"
          :disable="disabled()"
          style="margin-top: 20px"
          @click="login()"
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
    email: '',
    password: '',
  });

  let isPwd = ref(true);

  async function login() {
    await AuthService.login({
      email: userData.value.email,
      password: userData.value.password,
    });
    router.push('/main');
  }
  function disabled(): boolean
  {
    return !userData.value.email
      || !userData.value.password;
  }
</script>
