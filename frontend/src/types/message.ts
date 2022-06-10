export enum TextMessage {
  /** Ошибка входа */
  EnterError = 'enter-error',
  /** Ошибка */
  AnyError = 'any-error',
  /** Введите логин! */
  LoginError = 'login-error',
  /** Введите пароль! */
  PasswordError = 'password-error',
  /** Пароли должны совпадать! */
  ConfirmError = 'confirm-error',
  /** Введите почту! */
  EmailError = 'email-error',
}

export class TextError extends Error {
  constructor(public message: TextMessage) {
    super();
  }
}
