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
