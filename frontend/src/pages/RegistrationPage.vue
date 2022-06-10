<template>
  <div class="q-pa-md">
    <q-card class="my-card absolute-center" style="width: 400px; padding: 20px">
      <q-card-section>
        <q-input v-model="userData.name" label="Login" />
        <q-input v-model="userData.email" label="Email" />
        <q-input
          v-model="userData.password"
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
          style="margin-top: 20px"
          @click="registration()"
        />
      </q-card-section>
    </q-card>
  </div>
  <q-dialog v-model="registrationError.isOpen">
    <q-card style="width: 300px">
      <q-card-section>
        <div class="text-h6" style="font-size: 1em; text-align: center">
          {{ registrationError.message }}
        </div>
      </q-card-section>

      <q-card-actions align="right" class="bg-white text-teal">
        <q-btn flat label="OK" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="ts">
  import { defineComponent, ref } from 'vue';
  import AuthService from '../services/auth.service';
  import { TextMessage } from '../types/message';
  import { Translate } from '../types/translate';

  export default defineComponent({
    name: 'RegistrationPage',
    components: {},
    data() {
      return {
        userData: {
          name: '',
          email: '',
          password: '',
          confirmPassword: '',
        },
        isPwd: true,
        /** @TODO Сделать из этого компонент */
        registrationError: {
          message: '',
          isOpen: false,
          open(message: TextMessage) {
            this.message = Translate.get(message);
            this.isOpen = true;
          },
        },
      };
    },
    methods: {
      async registration() {
        try {
          await AuthService.registration({
            name: this.userData.name,
            email: this.userData.email,
            password: this.userData.password,
            confirmPassword: this.userData.confirmPassword,
          });
          this.$router.push('/login');
        } catch (e: any) {
          this.registrationError.open(e.message);
        }
      },
    },
  });
</script>
