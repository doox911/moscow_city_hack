/**
 * Api
 */
import ApiRequest from './ApiRequest';

/**
 * Service
 */
import AuthService from '../services/auth.service';

/**
 * Types
 */
import type { User } from 'Stores/userStore';
import type { AxiosRequestConfig } from 'axios';
import type { ResponseTokens } from 'Src/types';

export async function getUserInfo(): Promise<User | null> {
  const request = new ApiRequest();

  const response = await request.get<User>('api/user');

  return response && !(response instanceof Error) ? response : null;
}

export async function logout(): Promise<void> {
  /**
   * @TODO Фронт воткнули в back end. Для выхода из системы надо просто перейти по маршруту
   */
  const request = new ApiRequest();

  await request.post('api/');
}

/**
 * Регистрация обычного пользователя
 */
export async function apiSignupUser(
  data: object,
  config?: AxiosRequestConfig,
): Promise<unknown> {
  return new ApiRequest(config).post<ResponseTokens>('api/register', data);
}
/**
 * Выход пользователя из системы
 */
export async function apiLogoutAccess(config?: AxiosRequestConfig): Promise<unknown> {
  return new ApiRequest(config).post('api/logout', {});
}
/**
 * Вход пользователя в систему
 */
export async function apiLogin(data: object, config?: AxiosRequestConfig): Promise<unknown> {
  return new ApiRequest(config).post('api/login', data);
}

/**
 * @TODO Лучше перенести в более подходящее место
 */
ApiRequest.beforeRequest = (request) => {
  request.config.headers = {
    'content-type': 'application/json',
    Authorization: `Bearer ${AuthService.getToken()}`,
  };
};
