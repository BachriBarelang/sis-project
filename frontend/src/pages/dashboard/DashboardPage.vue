<script setup>
import { ref, onMounted } from 'vue'
import api from '@/plugins/axios'

import DashboardStats from './components/DashboardStats.vue'
import DashboardClassSummary from './components/DashboardClassSummary.vue'
import DashboardDistribution from './components/DashboardDistribution.vue'
import DashboardTodaySchedule from './components/DashboardTodaySchedule.vue'
import DashboardActivities from './components/DashboardActivities.vue'
import DashboardWarnings from './components/DashboardWarnings.vue'
import DashboardQuickActions from './components/DashboardQuickActions.vue'

const loading = ref(false)

const stats = ref({
  students: 0,
  teachers: 0,
  classes: 0,
  subjects: 0,
})

const classes = ref([])
const distribution = ref([])

const today = ref('')
const todaySchedules = ref([])
const activities = ref([])
const warnings = ref([])

const fetchDashboard = async () => {
  loading.value = true

  try {
    const response = await api.get('/dashboard')

    stats.value = response.data.stats
    classes.value = response.data.classes
    distribution.value = response.data.distribution
    today.value = response.data.today
    todaySchedules.value = response.data.today_schedules
    activities.value = response.data.activities
    warnings.value = response.data.warnings
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchDashboard)
</script>

<template>
  <div class="dashboard-container">

    <!-- LOADING -->
    <div
      v-if="loading"
      class="loading-card"
    >
      Loading dashboard data...
    </div>

    <!-- STATS -->
    <DashboardStats :stats="stats" />

    <!-- MAIN DASHBOARD -->
    <div class="dashboard-layout">

      <!-- LEFT CONTENT -->
      <div class="main-content">

        <DashboardClassSummary
          :classes="classes"
        />

        <DashboardDistribution
          class="section-spacing"
          :distribution="distribution"
        />

        <DashboardTodaySchedule
          class="section-spacing"
          :day="today"
          :schedules="todaySchedules"
        />

      </div>

      <!-- RIGHT SIDEBAR -->
      <div class="sidebar-content">

        <DashboardActivities
          :activities="activities"
        />

        <DashboardWarnings
          class="section-spacing"
          :warnings="warnings"
        />

        <DashboardQuickActions
          class="section-spacing"
        />

      </div>

    </div>

  </div>
</template>

<style scoped>
.dashboard-container {
  width: 100%;
}

.loading-card {
  padding: 16px;
  border-radius: 16px;
  margin-bottom: 16px;
  background: rgb(var(--v-theme-surface));
  color: rgb(var(--v-theme-on-surface));
}

.dashboard-layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 16px;
  margin-top: 16px;
}

.main-content {
  display: flex;
  flex-direction: column;
}

.sidebar-content {
  display: flex;
  flex-direction: column;
}

.section-spacing {
  margin-top: 16px;
}

/* OPTIONAL STICKY SIDEBAR */
/*
.sidebar-content {
  position: sticky;
  top: 80px;
  height: fit-content;
}
*/

@media (max-width: 960px) {
  .dashboard-layout {
    grid-template-columns: 1fr;
  }
}
</style>