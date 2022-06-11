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
    tasks: Task[],
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
      menu = (await new ApiRequest(config).post('api/tasks') as TaskResponce).content.tasks;
    }
  });

  return menu;
}