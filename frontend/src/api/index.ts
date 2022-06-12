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

export type OKVED = {
  code: string;
  name: string;
  additional_info: string;
}

/**
 * Получить список ОКВЭД
 */
export async function apiOKVED(str: string, config?: AxiosRequestConfig) {
  let list: OKVED[] = [];

  await requestWrapper({
    success: async () => {
      list = (await new ApiRequest(config).get('api/okved') as DefaultApiResponse<{ okved: OKVED[] }>).content.okved;
    },
    error_message: 'Ошибка загрузка ОКВЭД',
  });

  return list;
}