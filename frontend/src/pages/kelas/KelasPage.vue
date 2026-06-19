<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import api from '@/plugins/axios'

import BaseDataTable from '@/components/base/BaseDataTable.vue'

import ClassFormDialog from './components/ClassFormDialog.vue'
import ClassDeleteDialog from './components/ClassDeleteDialog.vue'
import ClassDetailDialog from './components/ClassDetailDialog.vue'

import { useClassStore } from '@/stores/class'

const classStore = useClassStore()
const route = useRoute()
const router = useRouter()

const dialog = ref(false)
const deleteDialog = ref(false)
const detailDialog = ref(false)

const snackbar = ref(false)
const snackbarText = ref('')

const selectedClass = ref(null)
const selectedId = ref(null)
const selectedClassId = ref(null)
const selectedPdfClass = ref(null)

const headers = [
  {
    title: 'Nama Kelas',
    key: 'name',
  },
  {
    title: 'Tingkat',
    key: 'level',
  },
  {
    title: 'Wali Kelas',
    key: 'homeroom_teacher',
  },
  {
    title: 'Jumlah Siswa',
    key: 'students_count',
  },
  {
    title: 'Aksi',
    key: 'actions',
    sortable: false,
  },
]

onMounted(async () => {
  await classStore.fetchClasses()

  if (route.query.create === '1') {
    selectedClass.value = null

    dialog.value = true

    router.replace({
      query: {},
    })
  }
})

const saveClass = async (data) => {
  let success = false

  if (data.id) {
    success = await classStore.editClass(
      data.id,
      data
    )
  } else {
    success = await classStore.addClass(
      data
    )
  }

  if (success) {
    dialog.value = false

    selectedClass.value = null

    snackbarText.value = data.id
      ? 'Kelas berhasil diupdate'
      : 'Kelas berhasil ditambahkan'

    snackbar.value = true

    await classStore.fetchClasses()
  }
}

const editClass = (schoolClass) => {
  selectedClass.value = schoolClass

  dialog.value = true
}

const openDeleteDialog = (schoolClass) => {
  selectedId.value = schoolClass.id

  deleteDialog.value = true
}

const deleteClass = async () => {
  const success =
    await classStore.removeClass(
      selectedId.value
    )

  if (success) {
    deleteDialog.value = false

    snackbarText.value =
      'Kelas berhasil dihapus'

    snackbar.value = true

    await classStore.fetchClasses()
  }
}

const openDetail = (schoolClass) => {
  selectedClassId.value = schoolClass.id

  detailDialog.value = true
}

const printSingleClass = async () => {
  if (!selectedPdfClass.value) return

  try {
    const response = await api.get(
      `/classes/${selectedPdfClass.value}/detail`
    )

    const data = response.data.class

    const doc = new jsPDF()

    doc.setFontSize(16)

    doc.text(
      `Data Kelas ${data.name}`,
      14,
      15
    )

    doc.setFontSize(11)

    doc.text(
      `Tingkat : ${data.level}`,
      14,
      25
    )

    doc.text(
      `Wali Kelas : ${
        data.homeroom_teacher?.name ?? '-'
      }`,
      14,
      32
    )

    doc.text(
      `Jumlah Siswa : ${data.students_count}`,
      14,
      39
    )

    autoTable(doc, {
      startY: 48,
      head: [
        [
          'No',
          'NIS',
          'Nama',
          'Gender',
        ],
      ],
      body: data.students.map(
        (student, index) => [
          index + 1,
          student.nis,
          student.name,
          student.gender,
        ]
      ),
    })

    doc.save(
      `kelas-${data.name}.pdf`
    )
  } catch (error) {
    console.error(error)
  }
}

const printAllClasses = async () => {
  try {
    const doc = new jsPDF()

    let firstPage = true

    for (const schoolClass of classStore.classes) {
      const response = await api.get(
        `/classes/${schoolClass.id}/detail`
      )

      const data = response.data.class

      if (!firstPage) {
        doc.addPage()
      }

      firstPage = false

      doc.setFontSize(16)

      doc.text(
        `Data Kelas ${data.name}`,
        14,
        15
      )

      doc.setFontSize(11)

      doc.text(
        `Tingkat : ${data.level}`,
        14,
        25
      )

      doc.text(
        `Wali Kelas : ${
          data.homeroom_teacher?.name ?? '-'
        }`,
        14,
        32
      )

      doc.text(
        `Jumlah Siswa : ${data.students_count}`,
        14,
        39
      )

      autoTable(doc, {
        startY: 48,
        head: [
          [
            'No',
            'NIS',
            'Nama',
            'Gender',
          ],
        ],
        body: data.students.map(
          (student, index) => [
            index + 1,
            student.nis,
            student.name,
            student.gender,
          ]
        ),
      })
    }

    doc.save(
      'seluruh-data-kelas.pdf'
    )
  } catch (error) {
    console.error(error)
  }
}

const createClass = () => {
  selectedClass.value = null
  dialog.value = true
}

</script>

<template>
  <BaseDataTable
    title="Data Kelas"
    :headers="headers"
    :items="classStore.classes"
  >
    <template #item.homeroom_teacher="{ item }">
      {{
        item.homeroom_teacher?.name
          ?? '-'
      }}
    </template>

    <template #item.students_count="{ item }">
      {{ item.students_count ?? 0 }}
    </template>

    <template #actions>
      <div class="d-flex ga-2">
        <v-select
          v-model="selectedPdfClass"
          :items="classStore.classes"
          item-title="name"
          item-value="id"
          label="Pilih Kelas"
          density="compact"
          hide-details
          style="width: 220px"
        />

        <v-btn
          color="success"
          prepend-icon="mdi-file-pdf-box"
          :disabled="!selectedPdfClass"
          @click="printSingleClass"
        >
          Cetak Kelas
        </v-btn>

        <v-btn
          color="warning"
          prepend-icon="mdi-printer"
          @click="printAllClasses"
        >
          Cetak Semua
        </v-btn>

        <v-btn
          color="primary"
          @click="createClass"
        >
          Tambah Kelas
        </v-btn>
      </div>
    </template>

    <template #item.actions="{ item }">
      <v-btn
        icon="mdi-eye"
        color="info"
        size="small"
        variant="text"
        @click="openDetail(item)"
      />

      <v-btn
        icon="mdi-pencil"
        size="small"
        variant="text"
        @click="editClass(item)"
      />

      <v-btn
        icon="mdi-delete"
        size="small"
        color="error"
        variant="text"
        @click="openDeleteDialog(item)"
      />
    </template>
  </BaseDataTable>

  <ClassFormDialog
    v-model="dialog"
    :school-class="selectedClass"
    @submit="saveClass"
  />

  <ClassDeleteDialog
    v-model="deleteDialog"
    @confirm="deleteClass"
  />

  <ClassDetailDialog
    v-model="detailDialog"
    :class-id="selectedClassId"
  />

  <v-snackbar
    v-model="snackbar"
    timeout="3000"
  >
    {{ snackbarText }}
  </v-snackbar>
</template>