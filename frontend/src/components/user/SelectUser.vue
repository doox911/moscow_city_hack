<template>
  <q-select 
    v-model="selected"
    :loading="loading"
    :options="userList"
    label="Выбрать пользователя"
    option-label="name"
  />
</template>

<script setup lang="ts">
  import { computed, onMounted, ref} from 'vue';

  /**
   * Api
   */
  import { apiGetAllUsers } from '../../api/users';

  /**
   * Stores
   */
  import { User } from '../../stores';

  const emit = defineEmits(['update:modelValue']);

  const props = withDefaults(defineProps<{
    modelValue?: User;
  }>(), {

  });

  const loading = ref(false);

  const userList = ref<User[]>([]);

  const selected = computed({
    get() {
      return props.modelValue;
    },
    set(v?: User) {
      emit('update:modelValue', v);
    }
  })

  async function updateUser() {
    const users = await apiGetAllUsers();

    userList.value = users;
  }

  onMounted(async () => {
    loading.value = true;
    
    await updateUser();

    loading.value = false;
  });

</script>
