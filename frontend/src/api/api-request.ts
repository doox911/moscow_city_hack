/**
  * Libraries
  */
import { api, axios } from 'Boot/axios';
/**
  * Types
  */
import type { AxiosError, AxiosRequestConfig, AxiosResponse } from 'axios'
export type RequestResponse<R> = Promise<R | AxiosError<unknown> | null>

export class APIRequest {

  static beforeRequest: (request: APIRequest) => void;

  public headers: Map<string, string> = new Map();
  constructor(public config: AxiosRequestConfig = {}) {}

  public async get<R>(url: string) {
    APIRequest.beforeRequest(this);

    const completeConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'get',
      data: { data: null },
    }

    return this.request<R>(completeConfig)
  }

  public async post<R>(url: string, data?: unknown) {
    APIRequest.beforeRequest(this);

    const completeConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'post',
      data,
    }
    return this.request<R>(completeConfig)
  }

  public async put<R>(url: string, data?: unknown) {
    APIRequest.beforeRequest(this);

    const completeConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'put',
      data,
    }

    return this.request<R>(completeConfig)
  }

  public async delete<R>(url: string) {
    APIRequest.beforeRequest(this);

    const completeConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'delete',
    }

    return this.request<R>(completeConfig)
  }

  private async request<R>(config: AxiosRequestConfig): RequestResponse<R> {
    // this.store.commit(`${ModuleName}/${MutationNames.ClearError}`)

    /* try { */
      const response = await api.request(config);

      return response
        ? response.data
        : null
    /* } catch (e) {
      const error = e as AxiosError<unknown>

      if (error.response?.data) {
        // const message = (error.response as AxiosResponse).data?.message

        // if (typeof message === 'string') {
        //   this.store.commit(`${ModuleName}/${MutationNames.SetError}`, message)
        // }
      }

      return error
    } */
  }
  private getCompleteConfig()
  {
    const completeConfig: any = {
      headers: {},
      ...this.config,
    }
    this.headers.forEach((value, key) => completeConfig.headers[key] = value);
    return completeConfig;
  }
 }
 