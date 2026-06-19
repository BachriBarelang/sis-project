import { defineStore } from 'pinia'

export const useSnackbarStore = defineStore(
  'snackbar',
  {
    state: () => ({
      show: false,
      text: '',
      color: 'success',
      timeout: 3000,
    }),

    actions: {
      success(message) {
        this.text = message
        this.color = 'success'
        this.show = true
      },

      error(message) {
        this.text = message
        this.color = 'error'
        this.show = true
      },

      warning(message) {
        this.text = message
        this.color = 'warning'
        this.show = true
      },
    },
  }
)