<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  modelValue: Boolean,

  teacher: {
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
  nip: '',
  name: '',
  gender: 'L',
  birth_date: '',
  phone: '',
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
  () => props.teacher,
  (teacher) => {
    if (!teacher) {
      form.id = null
      form.nip = ''
      form.name = ''
      form.gender = 'L'
      form.birth_date = ''
      form.phone = ''
      form.address = ''

      return
    }

    form.id = teacher.id
    form.nip = teacher.nip
    form.name = teacher.name
    form.gender = teacher.gender
    form.birth_date = teacher.birth_date
    form.phone = teacher.phone
    form.address = teacher.address
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
        {{ teacher ? 'Edit Guru' : 'Tambah Guru' }}
      </v-card-title>

      <v-card-text>

        <v-text-field
  v-model="form.nip"
  label="NIP"
/>

<v-text-field
  v-model="form.name"
  label="Nama Guru"
/>

<v-select
  v-model="form.gender"
  label="Jenis Kelamin"
  :items="[
    {
      title: 'Laki-Laki',
      value: 'L',
    },
    {
      title: 'Perempuan',
      value: 'P',
    },
  ]"
/>

<v-text-field
  v-model="form.birth_date"
  type="date"
  label="Tanggal Lahir"
/>

<v-text-field
  v-model="form.phone"
  label="No HP"
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