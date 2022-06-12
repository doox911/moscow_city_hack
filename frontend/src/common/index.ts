/**
 * Types
 */
import type { Counterparty } from 'Src/api/counterparty';
import { User } from '../stores';

/**
 * Constants
 */
import { Roles, RolesDescription } from 'Src/constants';
import { Good } from '../api/good';
import { Service } from '../api/service';

/**
 * первый символ в верхний регистр
 */
export function capitalizeFirstLetter(v: string) {
  return v.charAt(0).toUpperCase() + v.slice(1);
}

/**
 * Инициалы
 */
export function getUserName(user: { name: string, second_name: string, patronymic: string }) {
  const { name, second_name, patronymic } = user;

  const n = name?.length
    ? `${capitalizeFirstLetter(name)}`
    : ''

  const sn = second_name?.length
    ? `${capitalizeFirstLetter(second_name[0])}.`
    : ''

  const p = patronymic?.length
    ? `${capitalizeFirstLetter(patronymic[0])}.`
    : ''

  return `${n} ${sn} ${p}`;

};

export function getRoleDescription(name: Roles) {
  return RolesDescription[name];
}

export function setDateAndTimeToDateTimeComponent(date?: string) {
  if (date) {
    const d = new Date(date);
    const [_d] = date.split('T');

    const [year, month, day] = _d.split('-');

    const hours = `${d.getHours()}`.padStart(2, '0');
    const minutes = `${d.getMinutes()}`.padStart(2, '0');

    return `${day}.${month}.${year} ${hours}:${minutes}`;
  }

  return date;
}

/**
 * Русскоязычное отображение кол-ва выбранных записей в таблице
 */
export function selectedRowsLabel(count: number) {
  return `Выбрано ${count} записей`;
}

/**
 * Русскоязычное отображение пагинации в таблице
 */
export function paginationLabel(
  firstRowIndex: number,
  endRowIndex: number,
  totalRowsNumber: number,
) {
  return `${firstRowIndex} - ${endRowIndex} из ${totalRowsNumber}`;
}

/**
 * Предприятие по умолчанию
 */
export function getDefaultCounterparty(): Counterparty {
  return {
    id: null,
    user_id: null,
    name: '',
    full_name: '',
    inn: '',
    ogrn: '',
    adress: '',
    email: '',
    phone: '',
    site: '',
    goods: []
  }
}

/**
 * Пользователь по умолчанию
 */
export function getDefaultUser(): User {
  return {
    id: -1,
    company: null,
    name: '',
    second_name: '',
    patronymic: '',
    email: '',
    role: Roles.Guest,
    created_at: '',
    updated_at: '',
  }
}

export function getDefaultGood(): Good {
  return {
    name: '',
    brand: '',
    created_at: '',
    updated_at: ''
  }
}

export function getDefaultService(): Service {
  return {
    name: '',
    additional_info: '',
    code: ''
  }
}
