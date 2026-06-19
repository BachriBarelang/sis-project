<script setup>
import {
  reactive,
  watch,
  ref,
} from 'vue'

const props = defineProps({
  modelValue: Boolean,

  subject: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits([
  'update:modelValue',
  'submit',
])

const formRef = ref()

const serverErrors = ref({})

const rules = {
  required: value =>
    !!value || 'Wajib diisi',
}

const form = reactive({
  id: null,
  code: '',
  name: '',
  description: '',
})

const resetForm = () => {

  form.id = null
  form.code = ''
  form.name = ''
  form.description = ''

  serverErrors.value = {}
}

const closeDialog = () => {
  emit(
    'update:modelValue',
    false
  )
}

const submitForm = async () => {

  serverErrors.value = {}

  const { valid } =
    await formRef.value.validate()

  if (!valid) return

  emit('submit', {
    ...form,
    setErrors(errors) {
      serverErrors.value = errors
    },
  })
}

watch(
  () => props.subject,
  (subject) => {

    if (!subject) {
      resetForm()
      return
    }

    serverErrors.value = {}

    form.id = subject.id
    form.code = subject.code
    form.name = subject.name
    form.description =
      subject.description || ''

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
    @update:model-value="
      emit(
        'update:modelValue',
        $event
      )
    "
  >
    <v-card>

      <v-card-title>
        {{
          subject
            ? 'Edit Mata Pelajaran'
            : 'Tambah Mata Pelajaran'
        }}
      </v-card-title>

      <v-card-text>

        <v-form ref="formRef">

          <v-text-field
            v-model="form.code"
            label="Kode Mata Pelajaran"
            :rules="[rules.required]"
            :error-messages="
              serverErrors.code
            "
          />

          <v-text-field
            v-model="form.name"
            label="Nama Mata Pelajaran"
            :rules="[rules.required]"
            :error-messages="
              serverErrors.name
            "
          />

          <v-textarea
            v-model="form.description"
            label="Deskripsi"
            rows="3"
            :error-messages="
              serverErrors.description
            "
          />

        </v-form>

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