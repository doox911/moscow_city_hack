import ApiRequest from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';

/**
 * Types
 */
import type { PaginationCount, LaravelPagination } from 'Src/types';

export type Task = {
  user_id: number,
  entity_id: number,
  entity_type: string,
  value: string,
  is_moderated: boolean,
  is_accepted: boolean,
  comment: string,
};

export type TaskResponce = {
  content: PaginationCount & {
    tasks: LaravelPagination<Task>,
  },
  message: string;
}

/**
 * Получение списка задач
 */
export async function apiTasks(config?: AxiosRequestConfig) {
  let menu: Task[] = [];

  await requestWrapper({
    success: async () => {
      menu = (await new ApiRequest(config).get('api/tasks') as TaskResponce).content.tasks.data;
    }
  });

  return menu;
}