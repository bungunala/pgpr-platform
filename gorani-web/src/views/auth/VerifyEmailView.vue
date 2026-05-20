<template>
  <div class="page">
    <div class="container">
      <div class="text-center">
        <h2>Verificando correo electrónico...</h2>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api'

const route = useRoute()
const router = useRouter()

onMounted(async () => {
  const token = route.params.token
  
  try {
    await api.get(`/auth/verify-email/${token}`)
    alert('Correo verificado exitosamente. Ahora puede iniciar sesión.')
    router.push('/auth/login')
  } catch (error) {
    alert('Error al verificar el correo. El link puede haber expirado.')
    router.push('/auth/login')
  }
})
</script>