import { defineStore } from 'pinia'
import api from '@/api'

export const useEventsStore = defineStore('events', {
  state: () => ({
    events: [],
    currentEvent: null,
    myRegistrations: [],
    loading: false,
    error: null,
  }),

  getters: {
    activeEvents: (state) => state.events.filter(e => e.status_registration),
    currentEventSchedule: (state) => state.currentEvent?.schedule,
  },

  actions: {
    async fetchEvents(params = {}) {
      this.loading = true
      try {
        const response = await api.get('/events', { params })
        this.events = response.data
      } catch (error) {
        this.error = error.response?.data?.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchEvent(id) {
      this.loading = true
      try {
        const response = await api.get(`/events/${id}`)
        this.currentEvent = response.data
        return response.data
      } catch (error) {
        this.error = error.response?.data?.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchMyRegistrations() {
      this.loading = true
      try {
        const response = await api.get('/events/my-registrations')
        this.myRegistrations = response.data
      } catch (error) {
        this.error = error.response?.data?.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async registerForEvent(eventId, role) {
      this.loading = true
      try {
        const response = await api.post(`/events/${eventId}/register`, { role })
        await this.fetchMyRegistrations()
        return response.data
      } catch (error) {
        this.error = error.response?.data?.error || error.response?.data?.errors
        throw error
      } finally {
        this.loading = false
      }
    },

    async getEventSchedule(eventId) {
      try {
        const response = await api.get(`/events/${eventId}/schedule`)
        return response.data
      } catch (error) {
        throw error
      }
    },
  },
})