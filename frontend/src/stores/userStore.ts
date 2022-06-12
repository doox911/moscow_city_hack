import { defineStore } from 'pinia';
import { ref } from 'vue';

/**
 * Constants
 */
import { Roles } from 'Src/constants';

/**
 * Types
 */
import { Counterparty } from 'Src/api/counterparty';
import { apiGetAllUsers } from '../api/users';
import { getDefaultUser } from '../common';

export type User = {
  company?: Counterparty | null;
  created_at: string;
  email: string;
  id: number;
  name: string;
  patronymic: string;
  role: Roles;
  second_name: string;
  updated_at: string;
  password?: string;
};

export const userStore = defineStore('user', () => {
  const user = ref<User>(getDefaultUser());

  async function setUser(u: User) {
    user.value = u;
    user.value.role = user.value.role || Roles.Guest;
  }

  function removeUser() {
    user.value = {
      id: -1,
      company: null,
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

  const allUser = ref<User[]>([]);

  async function loadAllUser() {
    if (user.value.role == Roles.Admin) {
      const users = await apiGetAllUsers();
      allUser.value = users;
    }
  }

  return {
    removeUser,
    setUser,
    user,
    isAuthenticated,
    allUser,
    loadAllUser,
  };
});
