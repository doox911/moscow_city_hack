/**
 * Constants
 */
import { Roles, RolesDescription } from 'Src/constants';

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