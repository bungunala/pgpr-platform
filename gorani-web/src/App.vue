<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    try {
      await authStore.fetchUser()
    } catch (error) {
      localStorage.removeItem('token')
    }
  }
})
</script>

<style>
#app {
  min-height: 100vh;
}
</style>