<script setup>
import { useSnackbarStore }
from '@/stores/snackbar'

const snackbar = useSnackbarStore()
</script>

<template>
  <v-snackbar
    v-model="snackbar.show"
    :timeout="snackbar.timeout"
    :color="snackbar.color"
    location="top right"
  >
    {{ snackbar.text }}
  </v-snackbar>
</template>