import ApiRequest from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';

/**
 * Types
 */
import type { PaginationCount, LaravelPagination } from 'Src/types';

export type Counterparty = {
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

export type CounterpartyResponce = {
  content: PaginationCount & {
    counterparties: LaravelPagination<Counterparty>,
  },
  message: string;
}

/**
 * Получение списка предприятий
 */
export async function apiCounterparties(config?: AxiosRequestConfig) {
  let owner: Counterparty[] = [];
  console.log(config)
  await requestWrapper({
    success: async () => {
      owner = (await new ApiRequest(config).get('api/counterparties') as CounterpartyResponce).content.counterparties.data;
    }
  });

  return owner;
}