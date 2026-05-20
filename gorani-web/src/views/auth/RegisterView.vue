<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-card card">
        <h2>{{ $t('auth.register') }}</h2>
        
        <form @submit.prevent="handleRegister" class="mt-3">
          <div class="form-group">
            <label>{{ $t('auth.email') }} *</label>
            <input 
              v-model="form.email" 
              type="email" 
              :class="{ error: errors.email }"
              required
            />
            <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
          </div>

          <div class="form-group">
            <label>{{ $t('auth.password') }} *</label>
            <input 
              v-model="form.password" 
              type="password" 
              :class="{ error: errors.password }"
              required
            />
            <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
          </div>

          <div class="form-group">
            <label>{{ $t('auth.confirmPassword') }} *</label>
            <input 
              v-model="form.confirmPassword" 
              type="password" 
              :class="{ error: errors.confirmPassword }"
              required
            />
            <span v-if="errors.confirmPassword" class="error-message">
              {{ errors.confirmPassword }}
            </span>
          </div>

          <div class="form-group">
            <label>¿Cómo se inscribirá en el evento?</label>
            <select v-model="form.role" required>
              <option value="seller">Vendedor</option>
              <option value="buyer">Comprador</option>
              <option value="both">Ambos</option>
            </select>
          </div>

          <div class="form-group">
            <label>{{ $t('profile.identification') }} (RUC Ecuador - 13 dígitos)</label>
            <input 
              v-model="form.identification" 
              type="text" 
              maxlength="20"
              placeholder="Ej: 1791234567001"
            />
          </div>

          <div class="form-group">
            <label>{{ $t('profile.businessName') }}</label>
            <input v-model="form.business_name" type="text" />
          </div>

          <div class="form-group">
            <label>{{ $t('profile.country') }}</label>
            <select v-model="form.country_code">
              <option value="">Seleccionar país</option>
              <option v-for="country in countries" :key="country.code" :value="country.code">
                {{ country.name_es }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>
              <input type="checkbox" v-model="form.accept_terms" required />
              Acepto los términos y condiciones
            </label>
          </div>

          <div v-if="errors.general" class="error-message mb-2">
            {{ errors.general }}
          </div>

          <button type="submit" class="btn btn-primary w-100" :disabled="loading">
            {{ loading ? $t('common.loading') : $t('auth.register') }}
          </button>
        </form>

        <div class="mt-3 text-center">
          <p>
            {{ $t('auth.hasAccount') }}
            <router-link to="/auth/login">{{ $t('auth.signIn') }}</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/api'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  confirmPassword: '',
  role: 'seller',
  identification: '',
  business_name: '',
  country_code: '',
  accept_terms: false,
})

const loading = ref(false)
const errors = ref({})
const countries = ref([])

onMounted(async () => {
  try {
    const response = await api.get('/catalogs/countries')
    countries.value = response.data
  } catch (error) {
    console.error('Error loading countries:', error)
  }
})

const handleRegister = async () => {
  errors.value = {}
  
  if (form.value.password !== form.value.confirmPassword) {
    errors.value.confirmPassword = 'Las contraseñas no coinciden'
    return
  }

  if (!form.value.accept_terms) {
    errors.value.general = 'Debe aceptar los términos y condiciones'
    return
  }

  loading.value = true

  try {
    await authStore.register({
      email: form.value.email,
      password: form.value.password,
      role: form.value.role,
      identification: form.value.identification,
      business_name: form.value.business_name,
      country_code: form.value.country_code,
      accept_terms: form.value.accept_terms ? '1' : '0',
    })
    
    router.push('/')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      errors.value.general = error.response?.data?.error || 'Error de registro'
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
  padding: 2rem 0;
}

.auth-container {
  width: 100%;
  max-width: 500px;
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