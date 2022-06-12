import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';
import ApiRequest from './ApiRequest';

/**
 * Types
 */
import type { PaginationCount } from 'Src/types';

export type Service = {
  id?: number;
  name: string;
  additional_info: string;
  code: string;
};

export type ServiceResponse = {
  content: {
    service: Service;
  };
  message: string;
};

export type ServicesResponse = {
  content: PaginationCount & {
    services: Service[];
  };
  message: string;
};

/**
 * Получение списка услуг
 */
export async function apiServices(config?: AxiosRequestConfig) {
  let data: PaginationCount & {
    services: Service[];
  } = {
    pages_count: 0,
    total_rows: 0,
    services: [],
  };
  await requestWrapper({
    success: async () => {
      data = (
        (await new ApiRequest(config).get('/api/services')) as ServicesResponse
      ).content;
    },
  });
  return data;
}
