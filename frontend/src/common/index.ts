/**
 * первый символ в верхний регистр
 */
export function capitalizeFirstLetter(v: string) {
  return v.charAt(0).toUpperCase() + v.slice(1);
}