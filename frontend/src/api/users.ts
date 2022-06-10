
import Request from './Request'

/**
 * Types
 */
import type { User } from 'Stores/userStore'
import { APIRequest } from './api-request'
import AuthService from '../services/auth.service'

export async function getUserInfo(): Promise<User | null> {
  const request = new Request('/v0/user/')

  const response = await request.get<User>('info')

  return response && !(response instanceof Error)
    ? response
    : null
}

export async function logout(): Promise<void> {

  /**
   * @TODO Фронт воткнули в back end. Для выхода из системы надо просто перейти по маршруту
   */
  const request = new Request('/')

  await request.post('logout')
}

/**
 * Регистрация обычного пользователя
 */
export async function apiSignupUser(data: object, config?: any): Promise<any> {
  return new APIRequest(config).post(`api/register`, data);
}
/**
 * Выход пользователя из системы
 */
export async function apiLogoutAccess(config?: any): Promise<any> {
  return new APIRequest(config).post(`api/logout`, {});
}
/**
 * Вход пользователя в систему
 */
export async function apiLogin(data: object, config?: any): Promise<any> {
  return new APIRequest(config).post(`api/login`, data);
}
/** 
 * @TODO Лучше перенести в более подходящее место
 */
APIRequest.beforeRequest = (request) => {
  request.config.headers = {
    'content-type': 'application/json',
    'Authorization': `Bearer ${ AuthService.getToken() }`
  } 
}