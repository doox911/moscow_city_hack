import ApiRequest from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';

/**
 * Types
 */
import type { PaginationCount, LaravelPagination } from 'Src/types';

export type Counterpart = {
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
    counterparties: LaravelPagination<Counterpart>,
  },
  message: string;
}

/**
 * Получение списка предприятий
 */
export async function apiCounterparties(config?: AxiosRequestConfig) {
  let pagination: LaravelPagination<Counterpart> = {
    current_page: 1,
    data: [],
    first_page_url: '',
    from: '',
    next_page_url: '',
    path: '',
    per_page: '',
    prev_page_url: '',
    to: ''
  };
  await requestWrapper({
    success: async () => {
      pagination = (await new ApiRequest(config).get('api/counterparties') as CounterpartyResponce).content.counterparties;
    }
  });

  return pagination;
}