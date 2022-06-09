import type { QTableProps } from 'quasar';

export type ResetValidationMethods = {
  resetValidation: () => void;
  validation: () => void;
};

export type QTableOnRequestProps = Parameters<
  Required<QTableProps>['onRequest']
>[0];
