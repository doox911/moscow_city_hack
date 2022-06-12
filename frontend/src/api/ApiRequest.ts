/**
 * Libraries
 */
import { api } from 'Boot/axios';
/**
 * Types
 */
import type { AxiosError, AxiosRequestConfig } from 'axios';

export type ApiResponse<R> = {
  content: R;
  message: string;
};

export type RequestResponse<R> = Promise<
  ApiResponse<R> | AxiosError<unknown> | null
>;

export default class ApiRequest {
  static beforeRequest: (request: ApiRequest) => void;

  public headers: Map<string, string> = new Map();

  constructor(public config: AxiosRequestConfig = {}) {}

  public async get<R>(url: string) {
    ApiRequest.beforeRequest(this);

    const completeConfig: AxiosRequestConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'get',
      data: { data: null },
    };

    return this.request<R>(completeConfig);
  }

  public async post<R>(url: string, data?: unknown) {
    ApiRequest.beforeRequest(this);

    const completeConfig: AxiosRequestConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'post',
      data,
    };
    return this.request<R>(completeConfig);
  }

  public async put<R>(url: string, data?: unknown) {
    ApiRequest.beforeRequest(this);

    const completeConfig: AxiosRequestConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'put',
      data,
    };

    return this.request<R>(completeConfig);
  }

  public async delete<R>(url: string) {
    ApiRequest.beforeRequest(this);

    const completeConfig: AxiosRequestConfig = {
      url,
      ...this.getCompleteConfig(),
      method: 'delete',
    };

    return this.request<R>(completeConfig);
  }

  private async request<R>(config: AxiosRequestConfig): RequestResponse<R> {
    const response = await api.request(config);

    return response ? response.data : null;
  }
  private getCompleteConfig() {
    const completeConfig: AxiosRequestConfig = {
      headers: {},
      ...this.config,
    };

    this.headers.forEach((value, key) => (completeConfig.headers[key] = value));

    return completeConfig;
  }
}
