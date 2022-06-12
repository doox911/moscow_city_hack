export enum Roles {
  /**
   * Администратор
   */
  Admin = 'admin',

  /**
   * Правительственный персонал
   */
  Government = 'government',

  /**
   * Собственник компании
   */
  Owner = 'owner',

  /**
   * Гость
   */
  Guest = 'guest',
}

export const RolesDescription = Object.freeze({
  [Roles.Admin]: 'Администратор',
  [Roles.Government]: 'Сотрудник Департамента ИПП',
  [Roles.Owner]: 'Представитель компании',
  [Roles.Guest]: 'Гость',
});
