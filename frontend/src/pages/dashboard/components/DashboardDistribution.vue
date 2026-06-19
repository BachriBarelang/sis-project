<script setup>
import { computed } from 'vue'

import { Bar } from 'vue-chartjs'

import {
  Chart,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
} from 'chart.js'

import { ref } from 'vue'

const viewMode = ref('classes') 

Chart.register(
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend
)

const props = defineProps({
  distribution: {
    type: Array,
    required: true,
  },
})

/* =========================
   COLOR MAP
========================= */
const colorMap = {
  'X': '#4f46e5',       // indigo
  'XI': '#22c55e',      // green
  'XII': '#f59e0b',     // amber
  'default': '#3b82f6', // blue fallback
}

const getColor = (label) => {
  return colorMap[label] || colorMap.default
}

/* =========================
   CHART DATA (FIXED)
========================= */
const chartData = computed(() => {
  return {
    labels: props.distribution.map(item => item.level),

    datasets: [
      {
        label:
          viewMode.value === 'classes'
            ? 'Jumlah Kelas'
            : 'Jumlah Siswa',

        data: props.distribution.map(item =>
          viewMode.value === 'classes'
            ? item.total_classes
            : item.total_students
        ),

        backgroundColor: props.distribution.map(item =>
          getColor(item.level)
        ),

        borderRadius: 10,
        barThickness: 40,
      },
    ],
  }
})

/* =========================
   OPTIONS
========================= */
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,

  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      enabled: true,
    },
  },

  scales: {
    x: {
      grid: {
        display: false,
      },
    },
    y: {
      grid: {
        color: '#f1f5f9',
      },
      ticks: {
        precision: 0,
      },
    },
  },

  hover: {
    mode: 'index',
    animationDuration: 200,
  },
}
</script>

<template>
  <v-card class="card">
    <v-card-title class="title">
      Distribusi Tingkat
    </v-card-title>

    <div class="toggle">
      <v-btn
        size="small"
        :variant="viewMode === 'classes' ? 'flat' : 'outlined'"
        @click="viewMode = 'classes'"
      >
        Jumlah Kelas
      </v-btn>

      <v-btn
        size="small"
        :variant="viewMode === 'students' ? 'flat' : 'outlined'"
        @click="viewMode = 'students'"
      >
        Jumlah Siswa
      </v-btn>
    </div>

    <v-card-text>
      <div class="chart-wrapper">
        <Bar :data="chartData" :options="chartOptions" />
      </div>
    </v-card-text>
  </v-card>
</template>

<style scoped>
.card {
  border-radius: 16px;
  background: rgb(var(--v-theme-surface)) !important;
  color: rgb(var(--v-theme-on-surface));
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  transition: all 0.25s ease;
  
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.title {
  font-size: 16px;
  font-weight: 600;
  color: rgb(var(--v-theme-on-surface));
}

.toggle {
  display: flex;
  gap: 8px;
  margin: 0 16px 12px 16px;
}

.chart-wrapper {
  height: 350px;
  padding: 8px 4px;
}
</style>