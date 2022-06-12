/**
 * Обязательное поле
 */
export function requiredStringRule(
  v?: string | null,
  message = 'Обязательное поле',
) {
  if (!v) {
    return message;
  }

  return !!v.length || message;
}
export function requiredPasswordRule(
  password?: string,
  confirmPassword?: string,
  message = 'Пароли должны совпадать',
) {
  if (password != confirmPassword) {
    return message;
  }

  return true;
}

export function requiredSelectRule(e: { label: string; value: string }) {
  return requiredStringRule(e && e.value);
}
