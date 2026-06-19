import { defineStore } from 'pinia'

import {
  getTeachingAssignments,
  createTeachingAssignment,
  updateTeachingAssignment,
  deleteTeachingAssignment,
} from '@/services/TeachingAssignmentService'

export const useTeachingAssignmentStore =
  defineStore('teachingAssignment', {
    state: () => ({
      teachingAssignments: [],
      loading: false,
    }),

    actions: {
      async fetchTeachingAssignments() {
        try {
          this.loading = true

          this.teachingAssignments =
            await getTeachingAssignments()
        } catch (error) {
          console.error(error)
        } finally {
          this.loading = false
        }
      },

      async addTeachingAssignment(
        payload
      ) {
        try {
          await createTeachingAssignment(
            payload
          )

          await this.fetchTeachingAssignments()

          return true
        } catch (error) {
          throw error
        }
      },

      async editTeachingAssignment(
        id,
        payload
      ) {
        try {

          await updateTeachingAssignment(
            id,
            payload
          )

          await this.fetchTeachingAssignments()

          return true

        } catch (error) {

          throw error

        }
      },

      async removeTeachingAssignment(id) {
        try {

          await deleteTeachingAssignment(id)

          await this.fetchTeachingAssignments()

          return true

        } catch (error) {

          throw error

        }
      },
    },
  })