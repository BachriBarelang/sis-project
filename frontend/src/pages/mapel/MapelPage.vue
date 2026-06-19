<script setup>
import { ref, onMounted }
  from 'vue'

import BaseDataTable
  from '@/components/base/BaseDataTable.vue'

import MapelFormDialog
  from './components/MapelFormDialog.vue'

import MapelDeleteDialog
  from './components/MapelDeleteDialog.vue'

import { useSubjectStore }
  from '@/stores/subject'

import AppDeleteDialog
  from '@/components/common/AppDeleteDialog.vue'

import { useSnackbarStore }
  from '@/stores/snackbar'

const snackbar =
  useSnackbarStore()

const subjectStore =
  useSubjectStore()

const dialog = ref(false)

const deleteDialog = ref(false)

const selectedSubject = ref(null)

const selectedId = ref(null)

const headers = [
  {
    title: 'Kode',
    key: 'code',
  },
  {
    title: 'Nama',
    key: 'name',
  },
  {
    title: 'Deskripsi',
    key: 'description',
  },
  {
    title: 'Aksi',
    key: 'actions',
    sortable: false,
  },
]

onMounted(() => {
  subjectStore.fetchSubjects()
})

const saveSubject = async (data) => {
  try {

    if (data.id) {

      await subjectStore.editSubject(
        data.id,
        data
      )

      snackbar.success(
        'Mata pelajaran berhasil diperbarui'
      )

    } else {

      await subjectStore.addSubject(
        data
      )

      snackbar.success(
        'Mata pelajaran berhasil ditambahkan'
      )
    }

    dialog.value = false

    selectedSubject.value = null

  } catch (error) {

    if (
      error.response?.status === 422
    ) {

      const errors =
        error.response?.data?.errors

      if (errors) {

        data.setErrors?.(errors)

        const firstError =
          Object.values(errors)[0]?.[0]

        snackbar.error(firstError)

        return
      }
    }

    snackbar.error(
      error.response?.data?.message ||
      'Gagal menyimpan data'
    )
  }
}

const editSubject = (
  subject
) => {
  selectedSubject.value =
    subject

  dialog.value = true
}

const openDeleteDialog = (
  subject
) => {
  selectedId.value =
    subject.id

  deleteDialog.value = true
}

const deleteSubject = async () => {
  try {

    await subjectStore.removeSubject(
      selectedId.value
    )

    deleteDialog.value = false

    selectedId.value = null

    snackbar.success(
      'Mata pelajaran berhasil dihapus'
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
    title="Data Mata Pelajaran"
    :headers="headers"
    :items="subjectStore.subjects"
    :loading="subjectStore.loading"
  >
    <template #actions>
      <v-btn
        color="primary"
        @click="dialog = true"
      >
        Tambah Mata Pelajaran
      </v-btn>
    </template>

    <template
      #item.actions="{ item }"
    >
      <v-btn
        icon="mdi-pencil"
        size="small"
        variant="text"
        @click="
          editSubject(item)
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

  <MapelFormDialog
    v-model="dialog"
    :subject="
      selectedSubject
    "
    @submit="saveSubject"
  />

<AppDeleteDialog
  v-model="deleteDialog"
  title="Hapus Mata Pelajaran"
  message="Yakin ingin menghapus mata pelajaran ini?"
  @confirm="deleteSubject"
/>

</template>