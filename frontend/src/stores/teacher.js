import { defineStore } from 'pinia'

import {
  getTeachers,
  createTeacher,
  updateTeacher,
  deleteTeacher,
} from '@/services/TeacherService'

export const useTeacherStore = defineStore('teacher', {
  state: () => ({
    teachers: [],
    loading: false,
  }),

  actions: {
    async fetchTeachers() {
      try {
        this.loading = true

        this.teachers = await getTeachers()
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },

    async addTeacher(payload) {
      try {
        await createTeacher(payload)

        await this.fetchTeachers()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async editTeacher(id, payload) {
      try {
        await updateTeacher(id, payload)

        await this.fetchTeachers()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async removeTeacher(id) {
      try {
        await deleteTeacher(id)

        await this.fetchTeachers()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },
  },
})