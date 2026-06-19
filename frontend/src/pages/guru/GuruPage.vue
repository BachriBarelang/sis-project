<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import BaseDataTable from '@/components/base/BaseDataTable.vue'

import GuruFormDialog from './components/GuruFormDialog.vue'
import GuruDeleteDialog from './components/GuruDeleteDialog.vue'

import { useTeacherStore } from '@/stores/teacher'


import dayjs from 'dayjs'
import 'dayjs/locale/id'
dayjs.locale('id')

const formatDate = (date) => {
  if (!date) return '-'

  return dayjs(date).format('DD MMMM YYYY')
}

const teacherStore = useTeacherStore()
const route = useRoute()
const router = useRouter()

const dialog = ref(false)

const deleteDialog = ref(false)

const snackbar = ref(false)

const snackbarText = ref('')

const selectedTeacher = ref(null)

const selectedId = ref(null)

const headers = [
  {
    title: 'NIP',
    key: 'nip',
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
    title: 'No HP',
    key: 'phone',
  },
  {
    title: 'Aksi',
    key: 'actions',
    sortable: false,
  },
]

onMounted(async () => {
  await teacherStore.fetchTeachers()

  if (route.query.create === '1') {
    selectedTeacher.value = null

    dialog.value = true

    router.replace({
      query: {},
    })
  }
})

const saveTeacher = async (data) => {
  let success = false

  if (data.id) {
    success = await teacherStore.editTeacher(
      data.id,
      data
    )
  } else {
    success = await teacherStore.addTeacher(
      data
    )
  }

  if (success) {
    dialog.value = false

    selectedTeacher.value = null

    snackbarText.value = data.id
      ? 'Guru berhasil diupdate'
      : 'Guru berhasil ditambahkan'

    snackbar.value = true
  }
}

const editTeacher = (teacher) => {
  selectedTeacher.value = teacher

  dialog.value = true
}

const openDeleteDialog = (teacher) => {
  selectedId.value = teacher.id

  deleteDialog.value = true
}

const deleteTeacher = async () => {
  const success =
    await teacherStore.removeTeacher(
      selectedId.value
    )

  if (success) {
    deleteDialog.value = false

    snackbarText.value =
      'Guru berhasil dihapus'

    snackbar.value = true
  }
}
</script>

<template>
  <BaseDataTable
    title="Data Guru"
    :headers="headers"
    :items="teacherStore.teachers"
  >

      <template #item.birth_date="{ item }">
      {{ formatDate(item.birth_date) }}
    </template>

    <template #actions>
      <v-btn
        color="primary"
        @click="
          selectedTeacher = null;
          dialog = true;
        "
      >
        Tambah Guru
      </v-btn>
    </template>

    <template #item.actions="{ item }">
      <v-btn
        icon="mdi-pencil"
        size="small"
        variant="text"
        @click="editTeacher(item)"
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

  <GuruFormDialog
    v-model="dialog"
    :teacher="selectedTeacher"
    @submit="saveTeacher"
  />

  <GuruDeleteDialog
    v-model="deleteDialog"
    @confirm="deleteTeacher"
  />

  <v-snackbar
    v-model="snackbar"
    timeout="3000"
  >
    {{ snackbarText }}
  </v-snackbar>
</template>