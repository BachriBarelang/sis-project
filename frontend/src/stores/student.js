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

        return {
          success: true,
          message: 'Siswa berhasil ditambahkan',
        }

      } catch (error) {
        console.error(error)

        const message =
          error.response?.data?.errors?.nis?.[0] ||
          error.response?.data?.message ||
          'Gagal menambahkan siswa'

        return {
          success: false,
          message,
        }
      }
    },

  async editStudent(id, payload) {
    try {
      await updateStudent(id, payload)
      await this.fetchStudents()

      return {
        success: true,
        message: 'Siswa berhasil diupdate',
      }

    } catch (error) {
      console.error(error)

      const message =
        error.response?.data?.errors?.nis?.[0] ||
        error.response?.data?.message ||
        'Gagal mengupdate siswa'

      return {
        success: false,
        message,
      }
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