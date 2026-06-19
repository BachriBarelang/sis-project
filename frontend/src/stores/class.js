import { defineStore } from 'pinia'

import {
  getClasses,
  createClass,
  updateClass,
  deleteClass,
} from '@/services/ClassService'

export const useClassStore = defineStore('class', {
  state: () => ({
    classes: [],
    loading: false,
  }),

  actions: {
    async fetchClasses() {
      try {
        this.loading = true

        this.classes = await getClasses()
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },

    async addClass(payload) {
      try {
        await createClass(payload)

        await this.fetchClasses()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async editClass(id, payload) {
      try {
        await updateClass(id, payload)

        await this.fetchClasses()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async removeClass(id) {
      try {
        await deleteClass(id)

        await this.fetchClasses()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },
  },
})