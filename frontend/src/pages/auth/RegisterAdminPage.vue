<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import {
  registerAdmin,
} from '@/services/AuthService'

const router = useRouter()

const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const specialKey = ref('')

const loading = ref(false)
const error = ref('')
const name = ref('')


const submit = async () => {
  try {
    loading.value = true
    error.value = ''

    await registerAdmin({
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation:
        passwordConfirmation.value,
    special_key: specialKey.value,
    })
    
    router.push({
    path: '/login',
    query: {
        registered: '1',
    },
    })

  } catch (err) {

    error.value =
      err.response?.data?.message ||
      'Registrasi gagal'

  } finally {
    loading.value = false
  }
}


</script>

<template>
  <v-container
    class="fill-height"
  >
    <v-row
      justify="center"
      align="center"
    >
      <v-col
        cols="12"
        md="4"
      >
        <v-card>

          <v-card-title>
            Registrasi Admin
          </v-card-title>

          <v-card-text>

            <v-alert
              v-if="error"
              type="error"
              class="mb-4"
            >
              {{ error }}
            </v-alert>

            <v-text-field
              v-model="email"
              label="Email"
            />

            <v-text-field
            v-model="name"
            label="Nama Admin"
            />

            <v-text-field
              v-model="password"
              label="Password"
              type="password"
            />

            <v-text-field
              v-model="
                passwordConfirmation
              "
              label="Konfirmasi Password"
              type="password"
            />

            <v-text-field
              v-model="specialKey"
              label="Special Key"
              type="password"
              placeholder="Kontak admin untuk mendapatkan special key"
            />

          </v-card-text>

          <v-card-actions>

            <v-spacer />

            <v-btn
              variant="text"
              @click="
                router.push('/login')
              "
            >
              Kembali
            </v-btn>

            <v-btn
              color="primary"
              :loading="loading"
              @click="submit"
            >
              Registrasi
            </v-btn>

          </v-card-actions>

        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>