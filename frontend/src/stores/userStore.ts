import { defineStore } from 'pinia';
import { ref } from 'vue';

/**
 * Constants
 */
import { Roles } from 'Src/constants';

export type User = {
  id: number;
  company: number;
  name: string;
  second_name: string;
  patronymic: string;
  email: string;
  role: Roles;
  created_at: string;
  updated_at: string;
};

export const userStore = defineStore('user', () => {
  const user = ref<User>({
    id: -1,
    company: -1,
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
    user.value.role = user.value.role || Roles.Guest
  }

  function removeUser() {
    user.value = {
      id: -1,
      company: -1,
      name: '',
      second_name: '',
      patronymic: '',
      email: '',
      role: Roles.Guest,
      created_at: '',
      updated_at: '',
    };
  }
  function isAuthenticated() {
    return user.value.id !== -1;
  }

  return {
    removeUser,
    setUser,
    user,
    isAuthenticated,
  };
});
