import { defineStore } from 'pinia';
import { ref } from 'vue';

export type User = {
  id: number;
  name: string;
  email: string;
  email_verified_at?: null;
  created_at: string;
  updated_at: string;
};

export const userStore = defineStore('user', () => {
  const user = ref<User | null>(null);

  async function setUser(u: User) {
    user.value = u;
  }

  function removeUser() {
    user.value = null;
  }

  return {
    removeUser,
    setUser,
    user,
  };
});
