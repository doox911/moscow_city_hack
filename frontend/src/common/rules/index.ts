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
