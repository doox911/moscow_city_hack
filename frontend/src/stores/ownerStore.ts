import { defineStore } from 'pinia';
import { ref } from 'vue';

export type OwnerListItem = {
  id: number;
  name: string;
};
export type OwnerListItemForSelect = {
  label: string;
  value: number;
};

export const ownerStore = defineStore('ownerStore', () => {
  const ownerList = ref<OwnerListItemForSelect[]>([
    { value: 1, label: 'Предприятие 1' },
    { value: 2, label: 'Предприятие 2' },
    { value: 3, label: 'Предприятие 3' },
  ]);

  async function setList(u: OwnerListItem[]) {
    ownerList.value = u.map((item) => {
      return {
        value: item.id,
        label: item.name,
      };
    });
  }

  return {
    setList,
    ownerList,
  };
});
