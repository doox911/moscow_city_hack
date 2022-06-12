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
export async function apiRunParsing(
  str: string,
  timeinterval_id: ReturnType<typeof setInterval> | null,
  config?: AxiosRequestConfig,
) {
  await requestWrapper({
    success: async () => {
      await new ApiRequest(config).post(`api/parse/${str}`);
    },
    error_message: 'Ошибка запуска парсинга',
    success_message: 'Парсинг успешно запущен',
    error: () => {
      if (timeinterval_id) {
        clearInterval(timeinterval_id);
      }
    },
  });
}

/**
 * Запуск парсинга
 */
export async function apiStopParsing(config?: AxiosRequestConfig) {
  await requestWrapper({
    success: async () => {
      await new ApiRequest(config).get('api/cancel_parsing');
    },
    error_message: 'Ошибка остановки парсинга',
    success_message: 'Парсинг успешно остановлен',
  });
}

/**
 * Статус парсинга
 */
export async function apiPingParsing(config?: AxiosRequestConfig) {
  let status = true;

  await requestWrapper({
    success: async () => {
      status = (
        (await new ApiRequest(config).get(
          'api/check_parse_status',
        )) as DefaultApiResponse<{ status: boolean }>
      ).content.status;
    },
  });

  return status;
}
