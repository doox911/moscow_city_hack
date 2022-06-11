import { onMounted, ref } from 'vue';

/**
 * Routers
 */
import { useRoute, useRouter } from 'vue-router';

/**
 * Store
 */
import { storeToRefs } from 'pinia'
import { userStore } from '../stores/userStore';

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

export function useUserPageGuard() {
  const route = useRoute();

  const router = useRouter();

  const { user } = storeToRefs(userStore());

  onMounted(() => {
    if (route.name !== user.value.role) {
      router.push({
        name: user.value.role,
      })
    }
  });
}

export function useUserProfilePageGuard() {
  const route = useRoute();

  const router = useRouter();

  const { user } = storeToRefs(userStore());

  onMounted(() => {
    if (route.name !== user.value.role + 'Profile') {
      router.push({
        name: user.value.role + 'Profile',
      })
    }
  });
}