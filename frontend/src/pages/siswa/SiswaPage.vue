<script setup>
import { onMounted } from 'vue'

import { ref } from 'vue'

import { useRoute, useRouter } from 'vue-router'

import SiswaFormDialog from './components/SiswaFormDialog.vue'

import BaseDataTable from '@/components/base/BaseDataTable.vue'

import { useStudentStore } from '@/stores/student'

import dayjs from 'dayjs'
import 'dayjs/locale/id'
dayjs.locale('id')

const formatDate = (date) => {
  if (!date) return '-'

  return dayjs(date).format('DD MMMM YYYY')
}

const route = useRoute()
const router = useRouter()

const studentStore = useStudentStore()

const dialog = ref(false)

onMounted(() => {
  if (route.query.create === '1') {
    dialog.value = true

    router.replace({
      query: {},
    })
  }
})

const snackbar = ref(false)

const snackbarText = ref('')

const headers = [
  {
    title: 'NIS',
    key: 'nis',
  },

  {
    title: 'Nama',
    key: 'name',
  },

  {
    title: 'Jenis Kelamin',
    key: 'gender',
  },

  {
    title: 'Tanggal Lahir',
    key: 'birth_date',
  },

  {
    title: 'Aksi',
    key: 'actions',
    sortable: false,
  },
]

onMounted(() => {
  studentStore.fetchStudents()
})

const saveStudent = async (data) => {
  let result

  if (data.id) {
    result = await studentStore.editStudent(data.id, data)
  } else {
    result = await studentStore.addStudent(data)
  }

  if (result.success) {
    dialog.value = false
    selectedStudent.value = null
  }

  snackbarText.value = result.message
  snackbar.value = true
}

const selectedStudent = ref(null)

const editStudent = (student) => {
  selectedStudent.value = student

  dialog.value = true
}

const deleteDialog = ref(false)

const selectedId = ref(null)

const openDeleteDialog = (student) => {
  selectedId.value = student.id

  deleteDialog.value = true
}

const deleteStudent = async () => {
  const success =
    await studentStore.removeStudent(
      selectedId.value
    )

  if (success) {
    deleteDialog.value = false

    snackbarText.value =
      'Siswa berhasil dihapus'

    snackbar.value = true
  }
}

</script>

<template>
  <BaseDataTable
    title="Data Siswa"
    :headers="headers"
    :items="studentStore.students"
  >
    <template #item.birth_date="{ item }">
    {{ formatDate(item.birth_date) }}
    </template>
  
    <template #actions>
      <v-btn color="primary"@click="dialog = true">
        Tambah Siswa
      </v-btn>
    </template>

    <template #item.actions="{ item }">
    <v-btn
      icon="mdi-pencil"
      size="small"
      variant="text"
      @click="editStudent(item)"
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

  <SiswaFormDialog
    v-model="dialog"
    :student="selectedStudent"
    @submit="saveStudent"
  />

  <v-dialog
  v-model="deleteDialog"
  max-width="400"
>
  <v-card>

    <v-card-title>
      Hapus Siswa
    </v-card-title>

    <v-card-text>
      Apakah anda yakin ingin menghapus siswa ini?
    </v-card-text>

    <v-card-actions>

      <v-spacer />

      <v-btn
        variant="text"
        @click="deleteDialog = false"
      >
        Batal
      </v-btn>

      <v-btn
        color="error"
        @click="deleteStudent"
      >
        Hapus
      </v-btn>

    </v-card-actions>

  </v-card>
</v-dialog>

<v-snackbar
  v-model="snackbar"
  timeout="3000"
>
  {{ snackbarText }}
</v-snackbar>
</template>