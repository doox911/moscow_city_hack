import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';
import ApiRequest from './ApiRequest';

/**
 * Types
 */
import type { PaginationCount } from 'Src/types';

export type Good = {
  id?: number;
  brand: string;
  name: string;
  data_source?: {
    id: number;
    name: string;
    resource_name: string;
  }
  created_at: string
  updated_at: string

  data_source_id?: any
  data_source_item_id?: any
};

export type GoodResponce = {
  content: {
    good: Good
  }
  message: string;
}

export type GoodsResponce = {
  content: PaginationCount & {
    goods: Good[],
  },
  message: string;
}

/**
 * Получение списка товаров
 */
export async function apiGoods(config?: AxiosRequestConfig) {
  let data: PaginationCount & {
    goods: Good[],
  } = {
    pages_count: 0,
    total_rows: 0,
    goods: []
  };
  await requestWrapper({
    success: async () => {
      data = (await new ApiRequest(config).get('/api/goods') as GoodsResponce).content;
    }
  });
  return data;
}

/**
 * Создать товар
 */
export async function apiCreateGood(
  good: Good,
  config?: AxiosRequestConfig
) {
  await requestWrapper({
    success: async () => {
      (await new ApiRequest(config).post('/api/goods', good) as GoodResponce).content.good;
    },
    success_message: 'Товар добавлен'
  });
}

/**
 * Изменить товар
 */
export async function apiUpdateGood(
  good: Good,
  config?: AxiosRequestConfig
) {
  await requestWrapper({
    success: async () => {
      (await new ApiRequest(config).put(`/api/goods/${good.id}`, good) as GoodResponce).content.good;
    },
    success_message: 'Информация о товаре изменена успешно'
  });
}