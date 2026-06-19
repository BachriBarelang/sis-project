<script setup>
import { ref, watch } from 'vue'
import api from '@/plugins/axios'
import { computed } from 'vue'

const searchUnassigned = ref('')
const props = defineProps({
  modelValue: Boolean,
  classId: Number,
})

const emit = defineEmits([
  'update:modelValue',
])

const loading = ref(false)

const classData = ref(null)

const unassignedStudents = ref([])

const selectedStudents = ref([])

const fetchData = async () => {
  if (!props.classId) return

  loading.value = true

  try {
    const response = await api.get(
      `/classes/${props.classId}/detail`
    )

    classData.value =
      response.data.class

    unassignedStudents.value =
      response.data.unassigned_students

    selectedStudents.value = []
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const assignStudents = async () => {
  if (!selectedStudents.value.length) {
    return
  }

  try {
    await api.post(
      `/classes/${props.classId}/students`,
      {
        student_ids:
          selectedStudents.value,
      }
    )

    await fetchData()
  } catch (error) {
    console.error(error)
  }
}

const removeStudent = async (
  studentId
) => {
  try {
    await api.delete(
      `/classes/${props.classId}/students/${studentId}`
    )

    await fetchData()
  } catch (error) {
    console.error(error)
  }
}

watch(
  () => props.modelValue,
  async value => {
    if (value) {
      await fetchData()
    }
  }
)

const filteredUnassignedStudents = computed(() => {
  if (!searchUnassigned.value) {
    return unassignedStudents.value
  }

  const keyword =
    searchUnassigned.value.toLowerCase()

  return unassignedStudents.value.filter(
    student =>
      student.name
        .toLowerCase()
        .includes(keyword) ||
      student.nis
        .toLowerCase()
        .includes(keyword)
  )
})
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    max-width="1200"
    @update:model-value="
      emit('update:modelValue', $event)
    "
  >
    <v-card>
      <v-card-title>
        Detail Kelas
      </v-card-title>

      <v-card-text>
        <v-progress-linear
          v-if="loading"
          indeterminate
        />

        <template v-if="classData">
          <!-- INFO KELAS -->
          <v-alert
            color="info"
            variant="tonal"
            class="mb-4"
          >
            <div>
              <strong>Nama Kelas:</strong>
              {{ classData.name }}
            </div>

            <div>
              <strong>Tingkat:</strong>
              {{ classData.level }}
            </div>

            <div>
              <strong>Wali Kelas:</strong>
              {{
                classData
                  .homeroom_teacher
                  ?.name ?? '-'
              }}
            </div>

            <div>
              <strong>Jumlah Siswa:</strong>
              {{
                classData.students_count
              }}
            </div>
          </v-alert>

          <!-- SISWA DALAM KELAS -->
          <v-card
            variant="outlined"
            class="mb-4"
          >
            <v-card-title>
              Siswa Dalam Kelas
            </v-card-title>

            <v-table>
              <thead>
                <tr>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th>Gender</th>
                  <th width="120">
                    Aksi
                  </th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="student in classData.students"
                  :key="student.id"
                >
                  <td>
                    {{ student.nis }}
                  </td>

                  <td>
                    {{ student.name }}
                  </td>

                  <td>
                    {{ student.gender }}
                  </td>

                  <td>
                    <v-btn
                      size="small"
                      color="error"
                      @click="
                        removeStudent(
                          student.id
                        )
                      "
                    >
                      Keluarkan
                    </v-btn>
                  </td>
                </tr>

                <tr
                  v-if="
                    !classData.students.length
                  "
                >
                  <td
                    colspan="4"
                    class="text-center"
                  >
                    Belum ada siswa
                    dalam kelas
                  </td>
                </tr>
              </tbody>
            </v-table>
          </v-card>
          

          <!-- SISWA BELUM PUNYA KELAS -->
          <v-card
            border="start"
            color="error"
            variant="tonal"
        >
<v-alert
  color="error"
  variant="tonal"
  class="mb-3"
  icon="mdi-alert-circle"
>
  Terdapat
  <strong>
    {{ unassignedStudents.length }}
  </strong>
  siswa yang belum memiliki kelas.
</v-alert>

          <v-text-field
            v-model="searchUnassigned"
            label="Cari NIS atau Nama Siswa"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="compact"
            class="mb-3"
            />
            <v-card-text>
              <v-table>
                <thead>
                  <tr>
                    <th width="60">
                      Pilih
                    </th>

                    <th>NIS</th>

                    <th>Nama</th>

                    <th>Gender</th>
                  </tr>
                </thead>

                <tbody>
                  <tr
                    v-for="student in filteredUnassignedStudents"
                    :key="student.id"
                  >
                    <td>
                      <v-checkbox
                        v-model="
                          selectedStudents
                        "
                        :value="
                          student.id
                        "
                        hide-details
                      />
                    </td>

                    <td>
                      {{ student.nis }}
                    </td>

                    <td>
                      {{ student.name }}
                    </td>

                    <td>
                      {{
                        student.gender
                      }}
                    </td>
                  </tr>

                  <tr
                    v-if="
                      !unassignedStudents.length
                    "
                  >
                    <td
                      colspan="4"
                      class="text-center"
                    >
                      Semua siswa
                      sudah memiliki
                      kelas
                    </td>
                  </tr>
                </tbody>
              </v-table>

              <div
                class="d-flex justify-end mt-4"
              >
                <v-btn
                  color="primary"
                  :disabled="
                    !selectedStudents.length
                  "
                  @click="
                    assignStudents
                  "
                >
                  Masukkan ke Kelas
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </template>
      </v-card-text>

      <v-card-actions>
        <v-spacer />

        <v-btn
          color="secondary"
          @click="
            emit(
              'update:modelValue',
              false
            )
          "
        >
          Tutup
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>