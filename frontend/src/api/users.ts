
import Request from './Request'

/**
 * Types
 */
import type { User } from 'Stores/userStore'

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
