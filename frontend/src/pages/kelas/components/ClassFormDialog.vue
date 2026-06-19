<script setup>
import {
  reactive,
  watch,
  ref,
  onMounted,
} from 'vue'

import { getTeachers }
  from '@/services/TeacherService'

const props = defineProps({
  modelValue: Boolean,

  schoolClass: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits([
  'update:modelValue',
  'submit',
])

const teachers = ref([])

const form = reactive({
  id: null,
  name: '',
  level: '',
  homeroom_teacher_id: null,
})

onMounted(async () => {
  teachers.value =
    await getTeachers()
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
  () => props.schoolClass,
  (schoolClass) => {
    if (!schoolClass) {
      form.id = null
      form.name = ''
      form.level = ''
      form.homeroom_teacher_id = null

      return
    }

    form.id = schoolClass.id
    form.name = schoolClass.name
    form.level = schoolClass.level
    form.homeroom_teacher_id =
      schoolClass.homeroom_teacher_id
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
        {{
          schoolClass
            ? 'Edit Kelas'
            : 'Tambah Kelas'
        }}
      </v-card-title>

      <v-card-text>

        <v-text-field
          v-model="form.name"
          label="Nama Kelas"
        />

        <v-select
          v-model="form.level"
          label="Tingkat"
          :items="[
            'X',
            'XI',
            'XII',
          ]"
        />

        <v-select
          v-model="form.homeroom_teacher_id"
          label="Wali Kelas"
          :items="teachers"
          item-title="name"
          item-value="id"
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