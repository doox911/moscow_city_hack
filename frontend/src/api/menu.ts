import ApiRequest, { ApiResponse } from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { MenuListItem } from '../stores/menuStore';
import { requestWrapper } from '../common/wrappers';

/**
 * Получение списка меню
 */
export async function apiMenuList(config?: AxiosRequestConfig): Promise<MenuListItem[]> {
  let menu: MenuListItem[] = [];

  await requestWrapper({
    success: async () => {
      menu = (await new ApiRequest(config).post<MenuListItem[]>('api/menu') as ApiResponse<MenuListItem[]>).content;
    }
  });
  
  return menu;
}