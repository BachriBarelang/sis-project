<script setup>
import { ref, watch } from 'vue'
import api from '@/plugins/axios'
import { useSnackbarStore } from '@/stores/snackbar'

const snackbar = useSnackbarStore()

const props = defineProps({
  modelValue: Boolean,
  item: Object,
})

const emit = defineEmits([
  'update:modelValue',
  'saved',
])

const formRef = ref()

const loading = ref(false)

const assignments = ref([])

const serverErrors = ref({})

const rules = {
  required: value => !!value || 'Wajib diisi',
}

const days = [
  'Senin',
  'Selasa',
  'Rabu',
  'Kamis',
  'Jumat',
  'Sabtu',
]

const form = ref({
  teaching_assignment_id: null,
  day: '',
  start_time: '',
  end_time: '',
})

const resetForm = () => {
  form.value = {
    teaching_assignment_id: null,
    day: '',
    start_time: '',
    end_time: '',
  }

  serverErrors.value = {}
}

const loadAssignments = async () => {
  const res = await api.get('/teaching-assignments')

  assignments.value = res.data.map(item => ({
    ...item,
    label: `${item.school_class?.name} - ${item.subject?.name} - ${item.teacher?.name}`,
  }))
}

watch(
  () => props.modelValue,
  async opened => {
    if (!opened) return

    await loadAssignments()

    serverErrors.value = {}

    if (props.item) {
      form.value = {
        teaching_assignment_id:
          props.item.teaching_assignment_id,
        day: props.item.day,
        start_time: props.item.start_time,
        end_time: props.item.end_time,
      }
    } else {
      resetForm()
    }
  }
)

const close = () => {
  emit('update:modelValue', false)
}

const save = async () => {
  serverErrors.value = {}

  const { valid } =
    await formRef.value.validate()

  if (!valid) return

  loading.value = true

  try {
    if (props.item?.id) {
      await api.put(
        `/schedules/${props.item.id}`,
        form.value
      )

      snackbar.success(
        'Jadwal berhasil diperbarui'
      )
    } else {
      await api.post(
        '/schedules',
        form.value
      )

      snackbar.success(
        'Jadwal berhasil ditambahkan'
      )
    }

    emit('saved')
    close()

  } catch (error) {

    if (error.response?.status === 422) {

      serverErrors.value =
        error.response.data.errors || {}

      snackbar.error(
        error.response?.data?.message ||
        'Data tidak valid'
      )

      return
    }

    snackbar.error(
      error.response?.data?.message ||
      'Terjadi kesalahan'
    )

  } finally {
    loading.value = false
  }
}
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    max-width="700"
    @update:model-value="
      emit('update:modelValue', $event)
    "
  >
    <v-card>
      <v-card-title>
        {{ item ? 'Edit Jadwal' : 'Tambah Jadwal' }}
      </v-card-title>

      <v-card-text>

        <v-form ref="formRef">

          <v-select
            v-model="form.teaching_assignment_id"
            label="Pengampu"
            :items="assignments"
            item-title="label"
            item-value="id"
            :rules="[rules.required]"
            :error-messages="
              serverErrors.teaching_assignment_id
            "
          />

          <v-select
            v-model="form.day"
            label="Hari"
            :items="days"
            :rules="[rules.required]"
            :error-messages="
              serverErrors.day
            "
          />

          <v-text-field
            v-model="form.start_time"
            label="Jam Mulai"
            type="time"
            :rules="[rules.required]"
            :error-messages="
              serverErrors.start_time
            "
          />

          <v-text-field
            v-model="form.end_time"
            label="Jam Selesai"
            type="time"
            :rules="[rules.required]"
            :error-messages="
              serverErrors.end_time
            "
          />

        </v-form>

      </v-card-text>

      <v-card-actions>
        <v-spacer />

        <v-btn
          variant="text"
          @click="close"
        >
          Batal
        </v-btn>

        <v-btn
          color="primary"
          :loading="loading"
          @click="save"
        >
          Simpan
        </v-btn>

      </v-card-actions>
    </v-card>
  </v-dialog>
</template>