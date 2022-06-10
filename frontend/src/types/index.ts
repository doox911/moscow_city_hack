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
}

export type QueryWrapperOptions = {
  success: () => void;
  error?: (error?: unknown) => void;
  always?: () => void;
  success_message?: string;
  error_message?: string;
  always_message?: string;
};
