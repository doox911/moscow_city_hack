import ApiRequest, { ApiResponse } from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { MenuListItem } from '../stores/menuStore';
import { requestWrapper } from '../common/wrappers';

export type Owner = {
  id: number,
  user_id: number,
  name: string,
  full_name: string,
  inn: string,
  ogrn: string,
  adress: string,
  email: boolean,
  phone: string,
  site: string,
};

export type OwnerResponce = {
  content: {
    owner: Owner[],
  },
  message: string;
}

/**
 * Получение списка предприятий
 */
export async function apiOwner(config?: AxiosRequestConfig) {
  let owner: Owner[] = [];
  console.log(config)
  await requestWrapper({
    success: async () => {
      owner = (await new ApiRequest(config).get('api/counterparties') as OwnerResponce).content.owner;
    }
  });

  return owner;
}