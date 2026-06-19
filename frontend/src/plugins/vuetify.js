import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import { createVuetify } from 'vuetify'

export default createVuetify({
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        dark: false,
        colors: {
          background: '#f9fafb',
          surface: '#ffffff',
          primary: '#4f46e5',
        },
      },
      dark: {
        dark: true,
        colors: {
          background: '#0f172a',
          surface: '#1e293b',
          primary: '#6366f1',
        },
      },
    },
  },
})