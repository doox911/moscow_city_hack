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
 * Запуск парсинга
 */
export async function apiGetAllUsers(str: string, config?: AxiosRequestConfig) {
  await requestWrapper({
    success: async () => {
      await new ApiRequest(config).post(`api/parse/${str}`);
    },
    error_message: 'Ошибка запуска парсинга',
    success_message: 'Парсинг успешно запущен',
  });
}

/**
 * Статус парсинга
 */
export async function apiUpdateUserInfo(config?: AxiosRequestConfig) {
  let status = true;

  await requestWrapper({
    success: async () => {
      status = (await new ApiRequest(config).get('api/user') as DefaultApiResponse<{status: boolean}>).content.status;
    },
  });

  return status;
}
