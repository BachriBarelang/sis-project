import { defineStore } from 'pinia'
import api from '@/plugins/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,

    userName: (state) =>
      state.user?.name || 'Guest',

    userEmail: (state) =>
      state.user?.email || '',

    userRole: (state) =>
      state.user?.role || '',
  },

  actions: {
    async login(credentials) {
      try {
        const response = await api.post('/login', credentials)

        this.token = response.data.access_token

        localStorage.setItem('token', this.token)

        await this.fetchProfile()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async fetchProfile() {
      try {
        const response = await api.get('/profile')

        this.user = response.data

        return response.data
      } catch (error) {
        console.error(error)

        return null
      }
    },

    async logout() {
      try {
        await api.post('/logout')
      } catch (error) {
        console.error(error)
      }

      this.token = null
      this.user = null

      localStorage.removeItem('token')

      window.location.href = '/login'
    },
  },
})