import { defineStore } from 'pinia';
import { ref } from 'vue';

export type MenuListItem = {
  priority: number,
  name: string;
  title: string;
  iconName: string;
  to: string;
};

export const menuStore = defineStore('menuList', () => {
  const menuList = ref<MenuListItem[]>([]);

  async function setMenu(u: MenuListItem[]) {
    u.sort((a, b) => a.priority - b.priority);
    menuList.value = u;
  }

  return {
    setMenu,
    menuList
  };
});
