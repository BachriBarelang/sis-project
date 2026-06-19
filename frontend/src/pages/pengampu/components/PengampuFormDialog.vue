<script setup>
import {
  reactive,
  watch,
  ref,
  onMounted,
} from 'vue'

import { getTeachers }
  from '@/services/TeacherService'

import { getSubjects }
  from '@/services/SubjectService'

import { getClasses }
  from '@/services/ClassService'

const props = defineProps({
  modelValue: Boolean,

  assignment: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits([
  'update:modelValue',
  'submit',
])

const teachers = ref([])
const subjects = ref([])
const classes = ref([])

const form = reactive({
  id: null,
  teacher_id: null,
  subject_id: null,
  class_id: null,
  academic_year: '',
  semester: 'Ganjil',
})

onMounted(async () => {
  teachers.value =
    await getTeachers()

  subjects.value =
    await getSubjects()

  classes.value =
    await getClasses()
})

watch(
  () => props.assignment,
  (assignment) => {

    if (!assignment) {

      form.id = null
      form.teacher_id = null
      form.subject_id = null
      form.class_id = null
      form.academic_year = ''
      form.semester = 'Ganjil'

      return
    }

    form.id = assignment.id
    form.teacher_id =
      assignment.teacher_id

    form.subject_id =
      assignment.subject_id

    form.class_id =
      assignment.class_id

    form.academic_year =
      assignment.academic_year

    form.semester =
      assignment.semester
  },
  {
    immediate: true,
  }
)

const submitForm = () => {
  emit('submit', {
    ...form,
  })
}

const closeDialog = () => {
  emit(
    'update:modelValue',
    false
  )
}
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
          assignment
            ? 'Edit Pengampu'
            : 'Tambah Pengampu'
        }}
      </v-card-title>

      <v-card-text>

        <v-select
          v-model="form.teacher_id"
          label="Guru"
          :items="teachers"
          item-title="name"
          item-value="id"
        />

        <v-select
          v-model="form.subject_id"
          label="Mata Pelajaran"
          :items="subjects"
          item-title="name"
          item-value="id"
        />

        <v-select
          v-model="form.class_id"
          label="Kelas"
          :items="classes"
          item-title="name"
          item-value="id"
        />

        <v-text-field
          v-model="form.academic_year"
          label="Tahun Ajaran"
          placeholder="2026/2027"
        />

        <v-select
          v-model="form.semester"
          label="Semester"
          :items="[
            'Ganjil',
            'Genap',
          ]"
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