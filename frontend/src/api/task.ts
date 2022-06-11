import ApiRequest from './ApiRequest';
import { AxiosRequestConfig } from 'axios';
import { requestWrapper } from '../common/wrappers';

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
  content: {
    pages_count: number
    tasks: {
      current_page: number
      data: Task[]
      first_page_url: string
      from: string
      next_page_url: string
      path: string
      per_page: string
      prev_page_url: string
      to: string
    }
    total_rows: 0
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