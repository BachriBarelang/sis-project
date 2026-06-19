<script setup>
import {
  onMounted,
  ref,
  computed,
} from 'vue'

import api from '@/plugins/axios'

import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

import JadwalFormDialog
  from './components/JadwalFormDialog.vue'

import JadwalGrid
  from './components/JadwalGrid.vue'

import { useSnackbarStore }
  from '../../stores/snackbar.js'

import AppDeleteDialog
  from '@/components/common/AppDeleteDialog.vue'

import { useRoute, useRouter } from 'vue-router'

const schedules = ref([])
const classes = ref([])

const route = useRoute()
const router = useRouter()

const snackbar =
  useSnackbarStore()

const loading = ref(false)

const formDialog = ref(false)

const deleteDialog = ref(false)

const selectedItem = ref(null)

const selectedClass = ref(null)

onMounted(() => {
  if (route.query.create === '1') {
    selectedItem.value = null

    formDialog.value = true

    router.replace({
      query: {},
    })
  }
})


const viewMode = ref('table')

const headers = [
  { title: 'Kelas', key: 'class' },
  { title: 'Mapel', key: 'subject' },
  { title: 'Guru', key: 'teacher' },
  { title: 'Hari', key: 'day' },
  { title: 'Mulai', key: 'start_time' },
  { title: 'Selesai', key: 'end_time' },
  { title: 'Aksi', key: 'actions', sortable: false },
]

const fetchData = async () => {
  try {

    loading.value = true

    const { data } =
      await api.get('/schedules')

    schedules.value = data

  } catch {

    snackbar.error(
      'Gagal memuat data jadwal'
    )

  } finally {
    loading.value = false
  }
}

const fetchClasses = async () => {
  try {

    const { data } =
      await api.get('/classes')

    classes.value = data

  } catch {

    snackbar.error(
      'Gagal memuat data kelas'
    )
  }
}

const filteredSchedules =
  computed(() => {

    if (!selectedClass.value) {
      return schedules.value
    }

    return schedules.value.filter(
      item =>
        item.teaching_assignment
          ?.school_class?.id ===
        selectedClass.value
    )
  })

const selectedClassName = computed(() => {
  return classes.value.find(
    c => c.id === selectedClass.value
  )?.name || 'SEMUA KELAS'
})

const printPDF = () => {

  if (!selectedClass.value) {
    snackbar.error(
      'Pilih kelas terlebih dahulu'
    )
    return
  }

  const doc = new jsPDF()

  doc.setFontSize(14)

  doc.text(
    'JADWAL PELAJARAN',
    14,
    15
  )

  doc.setFontSize(11)

  doc.text(
    `Kelas: ${selectedClassName.value}`,
    14,
    23
  )

  const days = [
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu',
  ]

  const timeSlots = [
    ...new Set(
      filteredSchedules.value.map(
        s =>
          `${s.start_time} - ${s.end_time}`
      )
    ),
  ].sort()

  const body = []

  timeSlots.forEach(slot => {

    const row = [slot]

    days.forEach(day => {

      const schedule =
        filteredSchedules.value.find(
          s =>
            s.day === day &&
            `${s.start_time} - ${s.end_time}` ===
              slot
        )

      if (schedule) {

        row.push(
          `${schedule.teaching_assignment
            ?.subject?.name}\n${
            schedule.teaching_assignment
              ?.teacher?.name
          }`
        )

      } else {
        row.push('')
      }
    })

    body.push(row)
  })

  autoTable(doc, {
    startY: 30,
    head: [
      [
        'Jam',
        ...days,
      ],
    ],
    body,
    styles: {
      fontSize: 8,
      cellPadding: 2,
    },
  })

  doc.save(
    `jadwal-${selectedClassName.value}.pdf`
  )
}

const createItem = () => {
  selectedItem.value = null
  formDialog.value = true
}

const editItem = item => {
  selectedItem.value = item
  formDialog.value = true
}

const deleteItem = item => {
  selectedItem.value = item
  deleteDialog.value = true
}

const handleDelete = async () => {
  try {

    await api.delete(
      `/schedules/${selectedItem.value.id}`
    )

    snackbar.success(
      'Jadwal berhasil dihapus'
    )

    deleteDialog.value = false

    fetchData()

  } catch (error) {

    snackbar.error(
      error.response?.data?.message ||
      'Gagal menghapus jadwal'
    )
  }
}

onMounted(async () => {

  await fetchData()
  await fetchClasses()
})
</script>

<template>
  <v-card>

    <v-card-title
      class="
        d-flex
        justify-space-between
        align-center
        flex-wrap
        ga-2
      "
    >

      <span>
        Jadwal Pelajaran
      </span>

      <div
        class="d-flex ga-2"
      >

        <v-btn-toggle
          v-model="viewMode"
          mandatory
        >

          <v-btn
            value="table"
          >
            Tabel
          </v-btn>

          <v-btn
            value="grid"
          >
            Grid
          </v-btn>

          <v-btn
            color="success"
            prepend-icon="mdi-printer"
            @click="printPDF"
          >
            Cetak PDF
          </v-btn>

        </v-btn-toggle>

        <v-btn
          color="primary"
          @click="createItem"
        >
          Tambah Jadwal
        </v-btn>

      </div>

    </v-card-title>

    <v-card-text>

      <v-select
        v-model="selectedClass"
        label="Filter Kelas"
        :items="classes"
        item-title="name"
        item-value="id"
        clearable
        class="mb-4"
      />

      <template
        v-if="
          viewMode === 'table'
        "
      >

        <v-data-table
          :headers="headers"
          :items="
            filteredSchedules
          "
          :loading="loading"
        >

          <template
            #item.class="{ item }"
          >
            {{
              item.teaching_assignment
                ?.school_class?.name
            }}
          </template>

          <template
            #item.subject="{ item }"
          >
            {{
              item.teaching_assignment
                ?.subject?.name
            }}
          </template>

          <template
            #item.teacher="{ item }"
          >
            {{
              item.teaching_assignment
                ?.teacher?.name
            }}
          </template>

          <template
            #item.actions="{ item }"
          >

            <v-btn
              icon="mdi-pencil"
              variant="text"
              size="small"
              @click="
                editItem(item)
              "
            />

            <v-btn
              icon="mdi-delete"
              variant="text"
              size="small"
              color="error"
              @click="
                deleteItem(item)
              "
            />

          </template>

        </v-data-table>

      </template>

      <template
        v-else
      >

        <JadwalGrid
          :schedules="
            filteredSchedules
          "
        />

      </template>

    </v-card-text>

  </v-card>

  <JadwalFormDialog
    v-model="formDialog"
    :item="selectedItem"
    @saved="fetchData"
  />

  <AppDeleteDialog
    v-model="deleteDialog"
    title="Hapus Jadwal"
    message="Yakin ingin menghapus jadwal ini?"
    @confirm="handleDelete"
  />
</template>