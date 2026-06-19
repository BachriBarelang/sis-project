<script setup>
import { onMounted, ref } from 'vue'

import BaseDataTable from '@/components/base/BaseDataTable.vue'

import PengampuFormDialog
  from './components/PengampuFormDialog.vue'

import {
  useTeachingAssignmentStore,
} from '@/stores/teachingAssignment'

import { useSnackbarStore }
  from '@/stores/snackbar'

import AppDeleteDialog
from '@/components/common/AppDeleteDialog.vue'

const teachingAssignmentStore =
  useTeachingAssignmentStore()
  
const snackbar =
  useSnackbarStore()

const dialog = ref(false)

const deleteDialog = ref(false)

const selectedAssignment = ref(null)

const selectedId = ref(null)

const headers = [
  {
    title: 'Guru',
    key: 'teacher',
  },

  {
    title: 'Mata Pelajaran',
    key: 'subject',
  },

  {
    title: 'Kelas',
    key: 'school_class',
  },

  {
    title: 'Tahun Ajaran',
    key: 'academic_year',
  },

  {
    title: 'Semester',
    key: 'semester',
  },

  {
    title: 'Aksi',
    key: 'actions',
    sortable: false,
  },
]

onMounted(() => {
  teachingAssignmentStore
    .fetchTeachingAssignments()
})

  const saveAssignment = async (
    data
  ) => {
    try {

      if (data.id) {

        await teachingAssignmentStore
          .editTeachingAssignment(
            data.id,
            data
          )

        snackbar.success(
          'Pengampu berhasil diperbarui'
        )

      } else {

        await teachingAssignmentStore
          .addTeachingAssignment(
            data
          )

        snackbar.success(
          'Pengampu berhasil ditambahkan'
        )
      }

      dialog.value = false

      selectedAssignment.value = null

    } catch (error) {

      snackbar.error(
        error.response?.data?.message ||
        'Gagal menyimpan data'
      )

    }
  }

const editAssignment = (
  assignment
) => {

  selectedAssignment.value =
    assignment

  dialog.value = true
}

const openDeleteDialog = (
  assignment
) => {

  selectedId.value =
    assignment.id

  deleteDialog.value = true
}

const deleteAssignment = async () => {
  try {

    await teachingAssignmentStore
      .removeTeachingAssignment(
        selectedId.value
      )

    deleteDialog.value = false

    selectedId.value = null

    snackbar.success(
      'Pengampu berhasil dihapus'
    )

  } catch (error) {

    snackbar.error(
      error.response?.data?.message ||
      'Gagal menghapus data'
    )

  }
}
</script>

<template>
  <BaseDataTable
    title="Data Pengampu"
    :headers="headers"
    :items="
      teachingAssignmentStore
        .teachingAssignments
    "
    :loading="
      teachingAssignmentStore.loading
    "
  >

    <template #actions>
      <v-btn
        color="primary"
        @click="dialog = true"
      >
        Tambah Pengampu
      </v-btn>
    </template>

    <template #item.teacher="{ item }">
      {{ item.teacher?.name }}
    </template>

    <template #item.subject="{ item }">
      {{ item.subject?.name }}
    </template>

    <template #item.school_class="{ item }">
      {{ item.school_class?.name }}
    </template>

    <template #item.actions="{ item }">

      <v-btn
        icon="mdi-pencil"
        size="small"
        variant="text"
        @click="
          editAssignment(item)
        "
      />

      <v-btn
        icon="mdi-delete"
        size="small"
        color="error"
        variant="text"
        @click="
          openDeleteDialog(item)
        "
      />

    </template>

  </BaseDataTable>

  <PengampuFormDialog
    v-model="dialog"
    :assignment="
      selectedAssignment
    "
    @submit="saveAssignment"
  />

  <AppDeleteDialog
    v-model="deleteDialog"
    title="Hapus Pengampu"
    message="Yakin ingin menghapus data pengampu ini?"
    @confirm="deleteAssignment"
  />
</template>