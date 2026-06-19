<script setup>
import { ref,watch } from 'vue'

import { useAuthStore } from '@/stores/auth'

import AppSidebar from '../components/common/AppSidebar.vue'
import AppNavbar from '../components/common/AppNavbar.vue'
import { useTheme } from 'vuetify'

const auth = useAuthStore()

const drawer = ref(false)

const toggleDrawer = () => {
  drawer.value = !drawer.value
}

const handleLogout = async () => {
  await auth.logout()
}

//-------DARK MODE SETTING--------
const theme = useTheme()

const isDark = ref(false)

const toggleDark = () => {
  isDark.value = !isDark.value

  theme.global.name.value = isDark.value
    ? 'dark'
    : 'light'

  localStorage.setItem('theme', theme.global.name.value)
}

/* optional: persist */
watch(isDark, (val) => {
  localStorage.setItem('dark-mode', val)
})

const savedTheme = localStorage.getItem('theme')

if (savedTheme) {
  theme.global.name.value = savedTheme
  isDark.value = savedTheme === 'dark'
}

</script>

<template>
  <v-layout class="h-screen">

    <!-- Desktop Sidebar -->
    <div class="d-none d-md-flex">
      <AppSidebar />
    </div>

    <!-- Mobile Sidebar -->
    <v-navigation-drawer
      v-model="drawer"
      temporary
      class="d-md-none"
    >
      <AppSidebar />
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main class="app-main">

      <!-- Navbar -->
      <AppNavbar
        :toggleDrawer="toggleDrawer"
        :logout="handleLogout"
        :toggleDark="toggleDark"
        :isDark="isDark"
      />

      <!-- Page Content -->
      <v-container
        fluid
        class="pa-6 container-bg"
      >
        <router-view />
      </v-container>

    </v-main>

  </v-layout>
</template>

<style>

.app-main {
  background: rgb(var(--v-theme-background));
  min-height: 100vh;
  overflow-y: auto;
}

.container-bg {
  background: transparent !important;
  padding-bottom: 48px !important;
}

.v-application {
  background: rgb(var(--v-theme-background)) !important;
}
</style>