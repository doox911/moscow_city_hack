import { AxiosRequestConfig } from 'axios';
import { MenuListItem } from '../stores/menuStore';

/**
 * Получение списка меню
 */
export async function apiMenuList(
  config?: AxiosRequestConfig,
): Promise<MenuListItem[]> {
  let menu: MenuListItem[] = [];
  /* await requestWrapper({
    success: async () => {
      menu = (await new ApiRequest(config).post<MenuListItem[]>('api/get_menu') as ApiResponse<MenuListItem[]>).content;
    }
  }) */
  menu = [
    { title: 'Домой', name: '', iconName: 'material-icons-outlined', to: '/admin', priority: 0 },
    { title: 'Профиль', name: 'Профиль', iconName: 'material-icons-outlined', to: '/admin/profile', priority: 1 },
  ]
  return menu;
}