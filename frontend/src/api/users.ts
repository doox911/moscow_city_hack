/**
 * Api
 */
import ApiRequest, { ApiResponse } from './ApiRequest';

/**
 * Types
 */
import type { User } from 'Stores/userStore';
import type { AxiosRequestConfig } from 'axios';
import type { ResponseTokens } from 'Src/types';
import { requestWrapper } from '../common/wrappers';

/**
 * Информация о пользователе
 */
export async function apiGetUserInfo(config?: AxiosRequestConfig) {
  let user = null;
  await requestWrapper({
    success: async () => {
      user = (
        (await new ApiRequest(config).get<User>(
          'api/user',
        )) as ApiResponse<User>
      ).content;
    },
    error_message: 'Ошибка получения информации о пользователе',
  });

  return user;
}

/**
 * Информация о всех пользователях
 */
export async function apiGetAllUsers(config?: AxiosRequestConfig) {
  let users: User[] = [];
  await requestWrapper({
    success: async () => {
      users = (
        (await new ApiRequest(config).get<User[]>(
          'api/user/all',
        )) as ApiResponse<User[]>
      ).content;
    },
    error_message: 'Ошибка получения информации о пользователях',
  });

  return users;
}

/**
 * Изменить данные пользователя
 */
export async function apiUpdateUserInfo(
  user: User,
  config?: AxiosRequestConfig,
) {
  let response = null;
  await requestWrapper({
    success: async () => {
      response = (
        (await new ApiRequest(config).put<User>(
          'api/user',
          user,
        )) as ApiResponse<User>
      ).content;
    },
    error_message: 'Ошибка изменения информации о пользователях',
  });

  return response;
}

/**
 * Регистрация обычного пользователя
 */
export async function apiSignupUser(
  data: object,
  config?: AxiosRequestConfig,
): Promise<ResponseTokens | null> {
  let tokens: ResponseTokens | null = null;
  await requestWrapper({
    success: async () => {
      tokens = (
        (await new ApiRequest(config).post<ResponseTokens>(
          'api/register',
          data,
        )) as ApiResponse<ResponseTokens>
      ).content;
    },
    success_message: 'Добавлен новый пользователь',
  });
  return tokens;
}

/**
 * Выход пользователя из системы
 */
export async function apiLogoutAccess(
  config?: AxiosRequestConfig,
): Promise<unknown> {
  return new ApiRequest(config).post('api/logout', {});
}

/**
 * Вход пользователя в систему
 */
export async function apiLogin(
  data: object,
  config?: AxiosRequestConfig,
): Promise<ResponseTokens> {
  let tokens: ResponseTokens = { access_token: '', token_type: '' };
  await requestWrapper({
    success: async () => {
      tokens = (
        (await new ApiRequest(config).post(
          'api/login',
          data,
        )) as ApiResponse<ResponseTokens>
      ).content;
    },
    error_message: 'Ошибка входа',
  });
  return tokens;
}
