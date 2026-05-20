<template>
  <div class="page">
    <div class="container">
      <div class="hero">
        <h1>{{ $t('menu.home') }}</h1>
        <p>Plataforma de Matchmaking B2B</p>
      </div>

      <div class="mt-3" v-if="authStore.isAuthenticated">
        <h2>{{ $t('events.title') }}</h2>
        <div class="mt-2">
          <router-link to="/events" class="btn btn-primary">
            {{ $t('menu.events') }}
          </router-link>
        </div>

        <div class="mt-3" v-if="myRegistrations.length > 0">
          <h3>Mis Eventos</h3>
          <div class="events-grid mt-2">
            <div 
              v-for="reg in myRegistrations" 
              :key="reg.id" 
              class="card event-card"
            >
              <h4>{{ reg.event?.name_es }}</h4>
              <p>Estado: {{ reg.status }}</p>
              <router-link 
                :to="`/events/${reg.event_id}`" 
                class="btn btn-outline mt-2"
              >
                Ver Evento
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-3" v-else>
        <p>
          <router-link to="/auth/login" class="btn btn-primary">
            {{ $t('auth.login') }}
          </router-link>
          <router-link to="/auth/register" class="btn btn-outline ml-2">
            {{ $t('auth.register') }}
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useEventsStore } from '@/stores/events'

const authStore = useAuthStore()
const eventsStore = useEventsStore()

const myRegistrations = ref([])

onMounted(async () => {
  if (authStore.isAuthenticated) {
    await eventsStore.fetchMyRegistrations()
    myRegistrations.value = eventsStore.myRegistrations.map(reg => ({
      ...reg,
      event: eventsStore.events.find(e => e.id === reg.event_id)
    }))
  }
})
</script>

<style scoped>
.hero {
  text-align: center;
  padding: 3rem 0;
}

.hero h1 {
  font-size: 2.5rem;
  color: #2E7D32;
}

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.event-card {
  transition: transform 0.3s ease;
}

.event-card:hover {
  transform: translateY(-4px);
}

.ml-2 {
  margin-left: 1rem;
}
</style>