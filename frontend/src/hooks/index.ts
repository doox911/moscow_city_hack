import { ref } from 'vue';

/**
 * Используется при событиях: mouseover, mouseout
 */
export function useHover() {
  const is_hover = ref(false);

  function setHovering() {
    is_hover.value = true;
  }

  function unsetHovering() {
    is_hover.value = false;
  }

  return {
    is_hover,
    setHovering,
    unsetHovering,
  };
}
