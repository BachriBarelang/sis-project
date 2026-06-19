<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const authStore = useAuthStore()

const menus = [
  {
    title: 'Dashboard',
    icon: 'mdi-view-dashboard',
    to: '/dashboard',
    roles: ['admin', 'guru', 'operator'],
  },

  {
    title: 'Manajemen Siswa',
    icon: 'mdi-account-school',
    to: '/siswa',
    roles: ['admin', 'operator'],
  },

  {
    title: 'Manajemen Guru',
    icon: 'mdi-account-tie',
    to: '/guru',
    roles: ['admin'],
  },

  {
    title: 'Manajemen Kelas',
    icon: 'mdi-google-classroom',
    to: '/kelas',
    roles: ['admin', 'operator'],
  },

  {
    title: 'Mata Pelajaran',
    icon: 'mdi-book-open-page-variant',
    to: '/mapel',
    roles: ['admin', 'guru'],
  },

  {
    title: 'Pengampu',
    icon: 'mdi-account-supervisor',
    to: '/pengampu',
    roles: ['admin'],
  },

  {
    title: 'Jadwal Pelajaran',
    icon: 'mdi-calendar-clock',
    to: '/jadwal',
    roles: ['admin', 'guru'],
  },
  //UNUSED!!!!
  // {
  //   title: 'Absensi',
  //   icon: 'mdi-clipboard-check',
  //   to: '/absensi',
  //   roles: ['admin', 'guru'],
  // },

  // {
  //   title: 'Nilai Siswa',
  //   icon: 'mdi-chart-box',
  //   to: '/nilai',
  //   roles: ['admin', 'guru'],
  // },
]

const filteredMenus = computed(() => {
  return menus.filter(menu =>
    menu.roles.includes(authStore.userRole)
  )
})
</script>

<template>
  <v-navigation-drawer
    app
    permanent
  >
    <!-- User Info -->
    <v-list>
      <v-list-item>
        <template #prepend>
          <v-avatar>
            <v-icon>
              mdi-account-circle
            </v-icon>
          </v-avatar>
        </template>

        <v-list-item-title>
          {{ authStore.userName }}
        </v-list-item-title>

        <v-list-item-subtitle>
          {{ authStore.userRole }}
        </v-list-item-subtitle>
      </v-list-item>
    </v-list>

    <v-divider />

    <!-- Menu -->
    <v-list
      density="comfortable"
      nav
    >
      <v-list-item
        v-for="menu in filteredMenus"
        :key="menu.to"
        :to="menu.to"
        :prepend-icon="menu.icon"
        :title="menu.title"
        rounded="lg"
        :active="route.path === menu.to"
      />
    </v-list>
  </v-navigation-drawer>
</template>