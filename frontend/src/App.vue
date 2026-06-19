<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import AppSnackbar from '@/components/common/AppSnackbar.vue'

const authStore = useAuthStore()

onMounted(async () => {
  if (
    authStore.token &&
    !authStore.user
  ) {
    await authStore.fetchProfile()
  }
})
</script>

<template>
  <router-view />
  <AppSnackbar />
</template>