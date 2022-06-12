/**
 * Api
 */
import ApiRequest from './ApiRequest';

/**
 * Common
 */
import { requestWrapper } from 'Src/common/wrappers';

/**
 * Types
 */
import type { AxiosRequestConfig } from 'axios';
import { Counterparty } from './counterparty';
import { Good } from './good';

export type Service = {
  id?: number;
  name: string;
  additional_info: string;
  code: string;
};

export type AllEntity = {
  companies: Counterparty[]
  goods: Good[]
  services: Service[]
}
export type SearchResponce = {
  content: AllEntity
  message: string;
}

/**
 * Поиск
 */
export async function apiGlobalSearch(str: string, config?: AxiosRequestConfig) {
  let data: AllEntity = {
    companies: [],
    goods: [],
    services: []
  }
  await requestWrapper({
    success: async () => {
      data = (await new ApiRequest(config).post(`api/search/${str}`) as SearchResponce).content;
    },
    error_message: 'Ошибка запуска поиска',
  });

  return data;
}
