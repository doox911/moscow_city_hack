import { TextMessage } from "./message";

export const Translate = {
    translation: {
        // registration error
        'enter-error': 'Ошибка входа!',
        'any-error': 'Ошибка!',
        'login-error': 'Введите логин!',
        'password-error': 'Введите пароль!',
        'confirm-error': 'Пароли должны совпадать!',
        'email-error': 'Введите почту!',
    },
    get(message: TextMessage): string
    {
        return this.translation[message] || message;
    }
}