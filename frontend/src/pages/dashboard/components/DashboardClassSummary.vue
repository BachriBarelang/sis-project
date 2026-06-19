<script setup>
import { ref, computed,watch } from 'vue'

const props = defineProps({
  classes: {
    type: Array,
    required: true,
  },
})

const search = ref('')
const levelFilter = ref('Semua')

const page = ref(1)
const itemsPerPage = 7

watch([search, levelFilter], () => {
  page.value = 1
})

const filteredClasses = computed(() => {
  let result = props.classes

  // Search
  if (search.value) {
    const keyword = search.value.toLowerCase()

    result = result.filter(item =>
      item.name.toLowerCase().includes(keyword) ||
      (item.homeroom_teacher?.name || '')
        .toLowerCase()
        .includes(keyword)
    )
  }

  // Filter tingkat
  if (levelFilter.value !== 'Semua') {
    result = result.filter(
      item => item.level === levelFilter.value
    )
  }

  return result
})

const pageCount = computed(() =>
  Math.ceil(
    filteredClasses.value.length /
    itemsPerPage
  )
)

const paginatedClasses = computed(() => {
  const start =
    (page.value - 1) * itemsPerPage

  return filteredClasses.value.slice(
    start,
    start + itemsPerPage
  )
})
</script>

<template>
  <v-card class="class-card">

    <!-- HEADER -->
    <v-card-title class="title">
      Ringkasan Kelas
    </v-card-title>

    <!-- FILTER -->
    <v-card-text class="pb-0">
      <div class="toolbar">

        <v-text-field
          v-model="search"
          label="Cari kelas..."
          prepend-inner-icon="mdi-magnify"
          density="compact"
          variant="outlined"
          hide-details
          class="search"
        />

        <v-select
          v-model="levelFilter"
          :items="[
            'Semua',
            'X',
            'XI',
            'XII'
          ]"
          label="Tingkat"
          density="compact"
          variant="outlined"
          hide-details
          class="filter"
        />

      </div>
    </v-card-text>

    <!-- TABLE -->
    <v-table class="table">

      <thead>
        <tr>
          <th>Kelas</th>
          <th>Tingkat</th>
          <th>Wali Kelas</th>
          <th>Jumlah Siswa</th>
        </tr>
      </thead>

      <tbody>

        <tr
          v-for="item in paginatedClasses"
          :key="item.id"
          class="row"
        >
          <td class="name">
            {{ item.name }}
          </td>

          <td>
            <span class="badge">
              {{ item.level }}
            </span>
          </td>

          <td>
            {{
              item.homeroom_teacher?.name
              ?? '-'
            }}
          </td>

          <td>
            <span class="count">
              {{ item.students_count }}
            </span>
          </td>
        </tr>

        <!-- EMPTY STATE -->
        <tr
          v-if="
            paginatedClasses.length === 0
          "
        >
          <td
            colspan="4"
            class="empty"
          >
            Tidak ada data ditemukan
          </td>
        </tr>

      </tbody>

    </v-table>

    <!-- FOOTER -->
    <v-card-actions>

      <div class="text-caption">
        Total:
        {{ filteredClasses.length }}
        kelas
      </div>

      <v-spacer />

      <v-pagination
        v-model="page"
        :length="pageCount"
        rounded="circle"
        density="comfortable"
      />

    </v-card-actions>

  </v-card>
</template>

<style scoped>
.class-card {
  border-radius: 16px;
  background: rgb(var(--v-theme-surface));
  color: rgb(var(--v-theme-on-surface));
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* TITLE */
.title {
  font-weight: 600;
  font-size: 16px;
  color: rgb(var(--v-theme-on-surface));
}

/* TOOLBAR */
.toolbar {
  display: flex;
  gap: 12px;
  margin-bottom: 12px;
}

.search {
  flex: 1;
}

.filter {
  width: 140px;
}

/* TABLE */
.table {
  background: transparent;
}

/* HEADER TABLE */
thead th {
  color: rgb(var(--v-theme-on-surface)) !important;
  font-weight: 600;
  background: transparent !important;
}

/* Garis bawah header */
thead tr {
  border-bottom: 1px solid rgba(var(--v-theme-on-surface), 0.12);
}

/* ROW */
.row {
  transition: 0.2s;
}

.row:hover {
  background: rgba(0,0,0,0.04);
}

:global(.v-theme--dark) .row:hover {
  background: rgba(255,255,255,0.05);
}

/* NAME */
.name {
  font-weight: 600;
}

/* BADGE */
.badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
  background: rgba(99,102,241,0.15);
  color: rgb(var(--v-theme-on-surface));
}

/* COUNT */
.count {
  font-weight: 600;
}

/* EMPTY */
.empty {
  text-align: center;
  padding: 24px;
  opacity: 0.7;
}

/* MOBILE */
@media (max-width: 768px) {
  .toolbar {
    flex-direction: column;
  }

  .filter {
    width: 100%;
  }
}
</style>