<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const actions = [
  {
    title: 'Tambah Siswa',
    icon: 'mdi-account-plus',
    route: '/siswa',
  },
  {
    title: 'Tambah Guru',
    icon: 'mdi-school',
    route: '/guru',
  },
  {
    title: 'Tambah Kelas',
    icon: 'mdi-google-classroom',
    route: '/kelas',
  },
  {
    title: 'Tambah Jadwal',
    icon: 'mdi-calendar-plus',
    route: '/jadwal',
  },
]

const openCreateDialog = action => {
  router.push({
    path: action.route,
    query: {
      create: '1',
    },
  })
}
</script>

<template>
  <v-card>
    <v-card-title>
      Aksi Cepat
    </v-card-title>

    <v-card-text>
      <v-row>
        <v-col
          v-for="action in actions"
          :key="action.title"
          cols="6"
        >
          <v-btn
            block
            size="large"
            color="primary"
            variant="outlined"
            @click="openCreateDialog(action)"
          >
            <v-icon start>
              {{ action.icon }}
            </v-icon>

            {{ action.title }}
          </v-btn>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>