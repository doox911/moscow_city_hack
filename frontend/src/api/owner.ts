import ApiRequest, { ApiResponse } from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { MenuListItem } from '../stores/menuStore';
import { requestWrapper } from '../common/wrappers';

export type MenuResponse = ApiResponse<{
  menus: MenuListItem[]
}>

/**
 * Получение списка меню
 */
export async function apiOwner(config?: AxiosRequestConfig) {
  let menu: MenuListItem[] = [];

  await requestWrapper({
    success: async () => {
      menu = (await new ApiRequest(config).get('api/menu') as MenuResponse).content.menus;
    }
  });

  return menu;
}