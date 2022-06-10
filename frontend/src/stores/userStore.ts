import { defineStore } from 'pinia';
import { ref } from 'vue';

/**
 * Constants
 */
import { Roles } from 'Src/constants';

export type User = {
  id: number;
  login: string;
  name: string;
  second_name: string;
  patronymic: string;
  email: string;
  email_verified_at?: null;
  role: Roles;
  created_at: string;
  updated_at: string;
};

export const userStore = defineStore('user', () => {
  const user = ref<User>({
    id: -1,
    login: '',
    name: '',
    second_name: '',
    patronymic: '',
    email: '',
    role: Roles.Guest,
    created_at: '',
    updated_at: '',
  });

  async function setUser(u: User) {
    user.value = u;
  }

  function removeUser() {
    user.value = {
      id: -1,
      login: '',
      name: '',
      second_name: '',
      patronymic: '',
      email: '',
      role: Roles.Guest,
      created_at: '',
      updated_at: '',
    };
  }

  return {
    removeUser,
    setUser,
    user,
  };
});
