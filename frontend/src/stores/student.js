import { defineStore } from 'pinia'

import {
  getStudents,
  createStudent,
  updateStudent,
  deleteStudent,
} from '@/services/studentService'

export const useStudentStore = defineStore('student', {
  state: () => ({
    students: [],
    loading: false,
  }),

  actions: {
    async fetchStudents() {
      try {
        this.loading = true

        this.students = await getStudents()
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },

    async addStudent(payload) {
      try {
        await createStudent(payload)

        await this.fetchStudents()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async editStudent(id, payload) {
      try {
        await updateStudent(id, payload)

        await this.fetchStudents()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },

    async removeStudent(id) {
      try {
        await deleteStudent(id)

        await this.fetchStudents()

        return true
      } catch (error) {
        console.error(error)

        return false
      }
    },
  },
})