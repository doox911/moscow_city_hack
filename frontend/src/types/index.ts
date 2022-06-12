import type { QTableProps } from 'quasar';

export type ResetValidationMethods = {
  resetValidation: () => void;
  validation: () => void;
};

export type QTableOnRequestProps = Parameters<
  Required<QTableProps>['onRequest']
>[0];

export type ResponseTokens = {
  access_token: string;
  token_type: string;
};

export type QueryWrapperOptions = {
  success: () => void;
  error?: (error?: unknown) => void;
  always?: () => void;
  success_message?: string;
  error_message?: string;
  always_message?: string;
};

export type PaginationCount = {
  pages_count: number;
  total_rows: number;
};

export type LaravelPagination<T> = {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: string;
  next_page_url: string;
  path: string;
  per_page: string;
  prev_page_url: string;
  to: string;
};

export type ImportSortColumn = {
  [key: string]: 'asc' | 'desc';
};

export type DefaultApiResponse<T> = {
  content: T;
  message: string;
};
