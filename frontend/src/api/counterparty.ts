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
  email: string,
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
  let pagination: PaginationCount & {
    counterparties: LaravelPagination<Counterparty>,
  } = {
    pages_count: 0,
    total_rows: 0,
    counterparties: {
      current_page: 1,
      data: [],
      first_page_url: '',
      from: '',
      next_page_url: '',
      path: '',
      per_page: '',
      prev_page_url: '',
      to: ''
    }
  };
  await requestWrapper({
    success: async () => {
      pagination = (await new ApiRequest(config).get('/api/counterparties') as CounterpartyResponce).content;
    }
  });

  return pagination;
}