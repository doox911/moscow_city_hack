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
import type { DefaultApiResponse } from 'Src/types';

/**
 * Поиск
 */
export async function apiGlobalSearch(str: string, config?: AxiosRequestConfig) {
  await requestWrapper({
    success: async () => {
      await new ApiRequest(config).post(`api/search/${str}`);
    },
    error_message: 'Ошибка запуска поиска',
  });
}
