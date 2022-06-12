import ApiRequest, { ApiResponse } from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { MenuListItem } from 'Stores/menuStore';
import { requestWrapper } from 'Src/common/wrappers';

export type MenuResponse = ApiResponse<{
  menus: MenuListItem[];
}>;

/**
 * Получение списка меню
 */
export async function apiMenuList(config?: AxiosRequestConfig) {
  let menu: MenuListItem[] = [];

  await requestWrapper({
    success: async () => {
      menu = ((await new ApiRequest(config).get('api/menu')) as MenuResponse)
        .content.menus;
    },
  });

  return menu;
}
