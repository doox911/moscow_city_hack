<template>
    <div class="q-pa-md">
        <q-card class="my-card absolute-center" style = "width: 400px; padding: 20px;">
            <q-card-section>
                <q-input v-model="userData.name" label="Login"/>
                <q-input v-model="userData.password" :type="isPwd ? 'password' : 'text'" label="Password">
                    <template v-slot:append>
                        <q-icon
                            :name="isPwd ? 'visibility_off' : 'visibility'"
                            class="cursor-pointer"
                            @click="isPwd = !isPwd"/>
                    </template>
                </q-input>
                <q-btn color="primary float-right"
                    label="Login"
                    style = "margin-top: 20px"
                    @click="login()"/>
            </q-card-section>
        </q-card>
    </div>
    <q-dialog
        v-model="loginError.isOpen">
            <q-card style="width: 300px">
                <q-card-section>
                    <div class="text-h6" style = "font-size: 1em; text-align: center;">{{ loginError.message }}</div>
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
        name: 'LoginPage',
        components: { },
        data() {
            return {
                userData: {
                    name: "",
                    email: "",
                    password: "",
                    confirmPassword: "",
                },
                isPwd: true,
                /** @TODO Сделать из этого компонент */
                loginError: {
                    message: "",
                    isOpen: false,
                    open(message: TextMessage)
                    {
                        this.message = Translate.get(message);
                        this.isOpen = true;
                    }
                },
                mode: this.$route.meta.mode || 'login'
            }
        },
        setup() {
        },
        methods: {
            async login()
            {
                try {
                    await AuthService.login({
                        name: this.userData.name,
                        password: this.userData.password
                    });
                    this.$router.push("/main")
                }
                catch(e: any) {
                    this.loginError.open(e.message);
                }
            }
        }
    });
</script>
