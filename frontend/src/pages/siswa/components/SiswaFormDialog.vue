<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  modelValue: Boolean,

    student: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits([
  'update:modelValue',
  'submit',
])

const form = reactive({
    id: null,
    nis: '',
    name: '',
    gender: 'L',
    birth_date: '',
    address: '',
})

const closeDialog = () => {
  emit('update:modelValue', false)
}

const submitForm = () => {
  emit('submit', {
    ...form,
  })
}

watch(
  () => props.student,
  (student) => {
    if (!student) {
      form.id = null
      form.nis = ''
      form.name = ''
      form.gender = 'L'
      form.birth_date = ''
      form.address = ''

      return
    }

    form.id = student.id
    form.nis = student.nis
    form.name = student.name
    form.gender = student.gender
    form.birth_date = student.birth_date
    form.address = student.address
  },
  {
    immediate: true,
  }
)
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    max-width="600"
    @update:model-value="emit('update:modelValue', $event)"
  >
    <v-card>

      <v-card-title>
        {{ student ? 'Edit Siswa' : 'Tambah Siswa' }}
      </v-card-title>

      <v-card-text>

        <v-text-field
          v-model="form.nis"
          label="NIS"
        />

        <v-text-field
          v-model="form.name"
          label="Nama"
        />

        <v-select
          v-model="form.gender"
          label="Jenis Kelamin"
          :items="[
            { title: 'Laki-laki', value: 'L' },
            { title: 'Perempuan', value: 'P' },
          ]"
        />

        <v-text-field
          v-model="form.birth_date"
          label="Tanggal Lahir"
          type="date"
        />

        <v-textarea
          v-model="form.address"
          label="Alamat"
        />

      </v-card-text>

      <v-card-actions>

        <v-spacer />

        <v-btn
          variant="text"
          @click="closeDialog"
        >
          Batal
        </v-btn>

        <v-btn
          color="primary"
          @click="submitForm"
        >
          Simpan
        </v-btn>

      </v-card-actions>

    </v-card>
  </v-dialog>
</template>