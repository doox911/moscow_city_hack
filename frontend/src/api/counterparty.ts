import ApiRequest from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';

/**
 * Types
 */
import type { PaginationCount, LaravelPagination } from 'Src/types';
import { Good } from './good';
import { getDefaultCounterparty } from '../common';
import { Service } from './service';

export type Counterparty = {
  id: number | null,
  user_id: number | null,
  name: string,
  full_name: string,
  inn: string,
  ogrn: string,
  adress: string,
  email: string,
  phone: string,
  site: string,
  goods?: Good[],
  services?: Service[],
  created_at?: string;
  updated_at?: string;
};

export type CounterpartiesResponce = {
  content: PaginationCount & {
    counterparties: Counterparty[],
  },
  message: string;
}

export type CounterpartyResponce = {
  content: {
    counterparty: Counterparty
  }
  message: string;
}

/**
 * Получение списка предприятий
 */
export async function apiCounterparties(config?: AxiosRequestConfig) {
  let pagination: PaginationCount & {
    counterparties: Counterparty[],
  } = {
    pages_count: 0,
    total_rows: 0,
    counterparties: []
  };
  await requestWrapper({
    success: async () => {
      pagination = (await new ApiRequest(config).get('/api/counterparties') as CounterpartiesResponce).content;
    }
  });

  return pagination;
}

/**
 * Получить одно предприятие по user_id
 */
export async function apiCounterparty(
  id: number,
  config?: AxiosRequestConfig
) {
  let counterparty: Counterparty = getDefaultCounterparty();
  await requestWrapper({
    success: async () => {
      counterparty = (await new ApiRequest(config).get(`/api/counterparties/${id}`) as CounterpartyResponce).content.counterparty;
    }
  });
  return counterparty;
}

/**
 * Создать предприятие
 */
export async function apiCreateCounterparty(
  counterparty: Counterparty,
  config?: AxiosRequestConfig
) {
  await requestWrapper({
    success: async () => {
      (await new ApiRequest(config).post('/api/counterparties', counterparty) as CounterpartyResponce).content.counterparty;
    },
    success_message: 'Предприятие добавлено'
  });
}

/**
 * Изменить предприятие
 */
export async function apiUpdateCounterparty(
  counterparty: Counterparty,
  config?: AxiosRequestConfig
) {
  await requestWrapper({
    success: async () => {
      (await new ApiRequest(config).put(`/api/counterparties/${counterparty.id}`, counterparty) as CounterpartyResponce).content.counterparty;
    },
    success_message: 'Информация о предприятии изменена успешно'
  });
}

/**
 * Привязать к компании товар
 */
export async function apiCounterpartyAttachGoods(
  counterparty: Counterparty,
  goods: Good[],
  config?: AxiosRequestConfig
) {
  await requestWrapper({
    success: async () => {
      await new ApiRequest(config).post(`/api/counterparties/${counterparty.id}/attach_goods`, { goods });
    },
    success_message: 'К компании привязаны товары'
  });
}

/**
 * Привязать к компании услугу
 */
export async function apiCounterpartyAttachServices(
  counterparty: Counterparty,
  serviceIds: number[],
  config?: AxiosRequestConfig
) {
  await requestWrapper({
    success: async () => {
      await new ApiRequest(config).post(`/api/counterparties/${counterparty.id}/attach_services`, { services: serviceIds });
    },
    success_message: 'К компании привязаны услуги'
  });
}