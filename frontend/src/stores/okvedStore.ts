import { defineStore } from 'pinia';
import { ref } from 'vue';

/**
 * Api
 */
import { apiOKVED } from 'Src/api';

/**
 * Types 
 */
import type { OKVED } from 'Src/api';

export const okvedStore = defineStore('okved', () => {

  const okved = ref<OKVED[]>([]);

  async function loadOKVED() {
    okved.value = await apiOKVED();
  }
 
  return {
    okved,
    loadOKVED,
  };
});
