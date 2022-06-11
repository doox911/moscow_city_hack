import { defineStore } from 'pinia';
import { ref } from 'vue';

export type RequestListItem = {
  id: number
};

export const menuStore = defineStore('menuStore', () => {
  const requestList = ref<RequestListItem[]>([]);

  async function setList(u: RequestListItem[]) {
    requestList.value = u;
  }

  return {
    setList,
    requestList
  };
});
