<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-card card">
        <h2>{{ $t('auth.login') }}</h2>
        
        <form @submit.prevent="handleLogin" class="mt-3">
          <div class="form-group">
            <label>{{ $t('auth.email') }}</label>
            <input 
              v-model="email" 
              type="email" 
              :class="{ error: errors.email }"
              required
            />
            <span v-if="errors.email" class="error-message">
              {{ errors.email }}
            </span>
          </div>

          <div class="form-group">
            <label>{{ $t('auth.password') }}</label>
            <input 
              v-model="password" 
              type="password" 
              :class="{ error: errors.password }"
              required
            />
            <span v-if="errors.password" class="error-message">
              {{ errors.password }}
            </span>
          </div>

          <div v-if="errors.general" class="error-message mb-2">
            {{ errors.general }}
          </div>

          <button type="submit" class="btn btn-primary w-100" :disabled="loading">
            {{ loading ? $t('common.loading') : $t('auth.login') }}
          </button>
        </form>

        <div class="mt-3 text-center">
          <p>
            {{ $t('auth.noAccount') }}
            <router-link to="/auth/register">{{ $t('auth.signUp') }}</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const loading = ref(false)
const errors = ref({})

const handleLogin = async () => {
  errors.value = {}
  loading.value = true

  try {
    await authStore.login(email.value, password.value)
    
    const redirect = route.query.redirect || '/'
    router.push(redirect)
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      errors.value.general = error.response?.data?.error || 'Error de login'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f5f5f5;
}

.auth-container {
  width: 100%;
  max-width: 400px;
  padding: 1rem;
}

.auth-card h2 {
  text-align: center;
  color: #2E7D32;
}

.w-100 {
  width: 100%;
}
</style>