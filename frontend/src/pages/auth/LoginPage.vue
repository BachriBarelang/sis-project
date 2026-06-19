<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

import { useAuthStore } from '../../stores/auth'
const auth = useAuthStore()

const router = useRouter() 
import { useRoute } from 'vue-router'

const route = useRoute()

const email = ref('')
const password = ref('')
const loading = ref(false)
const snackbar = ref(false)
const snackbarText = ref('')

const handleLogin = async () => {
  try {
    loading.value = true

    const success = await auth.login({
      email: email.value,
      password: password.value,
    })

    if (success) {
      router.push('/dashboard')
    } else {
      alert('Login gagal')
    }

  } catch (error) {
    console.error(error)

    alert('Terjadi kesalahan')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (route.query.registered === '1') {

    snackbarText.value =
      'Registrasi admin berhasil, silakan login'

    snackbar.value = true

    router.replace({
      query: {},
    })
  }
})
</script>

<template>
  <v-container class="fill-height">
    <v-row align="center" justify="center">
      <v-col cols="12" md="4">

        <v-card class="pa-5">
          <h2 class="mb-5">Login SIS</h2>

          <v-text-field
            v-model="email"
            label="Email"
          />

          <v-text-field
            v-model="password"
            label="Password"
            type="password"
          />

          <v-btn
            color="primary"
            block
            :loading="loading"
            @click="handleLogin"
          >
            Login
          </v-btn>

          <div class="text-center mt-4">
          <v-btn
            variant="text"
            color="primary"
            @click="
              $router.push(
                '/register-admin'
              )
            "
          >
            Registrasi Baru
          </v-btn>
        </div>
        </v-card>

      </v-col>
    </v-row>
  </v-container>
  <v-snackbar
  v-model="snackbar"
  color="success"
  timeout="3000"
>
  {{ snackbarText }}
</v-snackbar>
</template>