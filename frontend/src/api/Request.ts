/**
 * Libraries
 */
import { api, axios } from 'Boot/axios';

/**
 * Types
 */
import type { AxiosError, AxiosResponse } from 'axios'

export type RequestResponse<R> = Promise<R | AxiosError<unknown> | null>

class Request {

  private readonly defaultConfig = {
    headers: {
      'content-type': 'application/json',
    },
  };

  private base_path: string;

  /**
   * @TODO По уму, надо сделать аргументом конструктора
   */
  // private store = Store;

  constructor(base_path = '') {
    this.base_path = base_path
  }

  public async get<R>(url: string, config = {}) {
    const complete_config = {
      ...this.defaultConfig,
      ...config,
      method: 'get',
      data: { data: null },
    }

    return this.request<R>(url, complete_config)
  }

  public async post<R>(url: string, data?: unknown, config = {}) {
    const complete_config = {
      ...this.defaultConfig,
      ...config,
      method: 'post',
      data,
    }

    return this.request<R>(url, complete_config)
  }

  public async put<R>(url: string, data?: unknown, config = {}) {
    const complete_config = {
      ...this.defaultConfig,
      ...config,
      method: 'put',
      data,
    }

    return this.request<R>(url, complete_config)
  }

  public async delete<R>(url: string, config = {}) {
    const complete_config = {
      ...this.defaultConfig,
      ...config,
      method: 'delete',
    }

    return this.request<R>(url, complete_config)
  }

  private async request<R>(url: string, config = {}): RequestResponse<R> {
    // this.store.commit(`${ModuleName}/${MutationNames.ClearError}`)

    try {
      const response = await axios(this.base_path + url, config)

      return response
        ? response.data
        : null
    } catch (e) {
      const error = e as AxiosError<unknown>

      if (error.response?.data) {
        // const message = (error.response as AxiosResponse).data?.message

        // if (typeof message === 'string') {
        //   this.store.commit(`${ModuleName}/${MutationNames.SetError}`, message)
        // }
      }

      return error
    }
  }

}

export default Request
