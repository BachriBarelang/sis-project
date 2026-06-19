<script setup>
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  toggleDrawer: Function,
  logout: Function,
  toggleDark: Function,
  isDark: Boolean,
})

const authStore = useAuthStore()
</script>

<template>
  <v-app-bar
    flat
    class="navbar"
    border
  >
    <!-- Mobile menu -->
    <v-app-bar-nav-icon
      class="d-md-none"
      @click="toggleDrawer"
    />

    <!-- Title -->
    <v-toolbar-title class="title">
      SIS Dashboard
    </v-toolbar-title>

    <v-spacer />

    <!-- Right section -->
    <div class="right-section">

      <!-- DARK MODE TOGGLE -->
      <v-btn
        icon
        variant="text"
        @click="toggleDark"
      >
        <v-icon>
          {{ isDark ? 'mdi-weather-sunny' : 'mdi-moon-waning-crescent' }}
        </v-icon>
      </v-btn>

      <!-- USER INFO -->
      <div class="user-info d-none d-sm-flex">

        <v-chip
          size="x-small"
          class="role-chip"
        >
          {{ authStore.userRole }}
        </v-chip>

        <div class="text-right">
          <div class="name">
            {{ authStore.userName }}
          </div>

          <div class="email">
            {{ authStore.userEmail }}
          </div>
        </div>
      </div>

      <!-- AVATAR -->
      <v-avatar size="36" class="avatar">
        <v-icon>
          mdi-account-circle
        </v-icon>
      </v-avatar>

      <!-- LOGOUT -->
      <v-btn
        class="logout-btn"
        variant="text"
        size="small"
        @click="logout"
      >
        Logout
      </v-btn>

    </div>
  </v-app-bar>
</template>

<style scoped>
.navbar {
  background: white;
  transition: 0.3s;
}

/* TITLE */
.title {
  font-weight: 600;
  font-size: 16px;
}

/* RIGHT SECTION */
.right-section {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* ROLE CHIP */
.role-chip {
  background: #e0e7ff;
  color: #3730a3;
  font-weight: 500;
}

/* USER INFO */
.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.name {
  font-weight: 600;
  font-size: 13px;
}

.email {
  font-size: 11px;
  color: #6b7280;
}

/* AVATAR */
.avatar {
  background: #f3f4f6;
}

/* LOGOUT */
.logout-btn {
  color: #ef4444;
  transition: 0.2s;
}

.logout-btn:hover {
  background: rgba(239, 68, 68, 0.08);
}

/* HOVER EFFECT GLOBAL */
.v-btn {
  transition: 0.2s;
}

.v-btn:active {
  transform: scale(0.95);
}
</style>