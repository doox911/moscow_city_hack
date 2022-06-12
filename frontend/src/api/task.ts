import ApiRequest from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';

/**
 * Types
 */
import type { PaginationCount } from 'Src/types';

export type Task = {
  user_id: number,
  entity_id: number,
  entity_type: string,
  value: string,
  is_moderated: 0 | 1,
  is_accepted: 0 | 1,
  comment: string,
};

export type TaskResponce = {
  content: PaginationCount & {
    tasks: Task[],
  },
  message: string;
}

/**
 * Получение списка задач
 */
export async function apiTasks(config?: AxiosRequestConfig) {
  let response: PaginationCount & {
    tasks: Task[],
  } = {
    pages_count: 0,
    total_rows: 0,
    tasks: [],
  };

  await requestWrapper({
    success: async () => {
      response = (await new ApiRequest(config).get('api/tasks') as TaskResponce).content;
    }
  });

  return response;
}