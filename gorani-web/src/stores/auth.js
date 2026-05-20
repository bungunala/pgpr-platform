import { defineStore } from 'pinia'
import api from '@/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    userProfile: (state) => state.user?.profile,
  },

  actions: {
    async login(email, password) {
      this.loading = true
      this.error = null
      try {
        const response = await api.post('/auth/login', { email, password })
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        return response.data
      } catch (error) {
        this.error = error.response?.data?.error || 'Login failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async register(data) {
      this.loading = true
      this.error = null
      try {
        const response = await api.post('/auth/register', data)
        this.token = response.data.token
        this.user = { id: response.data.user_id, email: data.email }
        localStorage.setItem('token', this.token)
        return response.data
      } catch (error) {
        this.error = error.response?.data?.errors || error.response?.data?.error || 'Registration failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchUser() {
      this.loading = true
      try {
        const response = await api.get('/auth/me')
        this.user = response.data
      } catch (error) {
        this.logout()
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateProfile(data) {
      this.loading = true
      try {
        const response = await api.put('/profile', data)
        if (this.user) {
          this.user.profile = response.data.profile
        }
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async changePassword(currentPassword, newPassword) {
      const response = await api.put('/auth/password', {
        current_password: currentPassword,
        new_password: newPassword,
      })
      return response.data
    },

    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
    },
  },
})