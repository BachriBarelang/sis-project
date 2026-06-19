import { defineStore } from 'pinia'

import {
  getSubjects,
  createSubject,
  updateSubject,
  deleteSubject,
} from '@/services/SubjectService'

export const useSubjectStore =
  defineStore('subject', {
    state: () => ({
      subjects: [],
      loading: false,
    }),

    actions: {
      async fetchSubjects() {
        try {
          this.loading = true

          this.subjects =
            await getSubjects()
        } catch (error) {
          console.error(error)
        } finally {
          this.loading = false
        }
      },

      async addSubject(payload) {
        try {

          await createSubject(payload)

          await this.fetchSubjects()

        } catch (error) {

          throw error
        }
    },

    async editSubject(id, payload) {
      try {

        await updateSubject(
          id,
          payload
        )

        await this.fetchSubjects()

      } catch (error) {

        throw error
      }
    },

      async removeSubject(id) {
        try {

          await deleteSubject(id)

          await this.fetchSubjects()

        } catch (error) {

          throw error
        }
      },
    },
  })