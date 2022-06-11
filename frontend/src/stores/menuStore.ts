import { defineStore } from 'pinia';
import { ref } from 'vue';

export type MenuListItem = {
  iconName: string;
  name: string;
  order: number,
  seporator: boolean
  title: string;
  to: string;
};

export const menuStore = defineStore('menuStore', () => {
  const menuList = ref<MenuListItem[]>([]);

  async function setMenu(u: MenuListItem[]) {
    u.sort((a, b) => a.order - b.order);

    menuList.value = u;
  }

  return {
    setMenu,
    menuList
  };
});
