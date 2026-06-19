<script setup>
defineProps({
  day: {
    type: String,
    default: '',
  },

  schedules: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <v-card>
    <v-card-title>
      Jadwal Hari Ini
      <span
        class="text-grey-darken-1 ml-2"
      >
        ({{ day }})
      </span>
    </v-card-title>

    <v-table>
      <thead>
        <tr>
          <th>Jam</th>
          <th>Kelas</th>
          <th>Mata Pelajaran</th>
          <th>Guru</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="item in schedules"
          :key="item.id"
        >
          <td>
            {{ item.start_time }}
            -
            {{ item.end_time }}
          </td>

          <td>
            {{
              item
                .teaching_assignment
                ?.school_class
                ?.name ?? '-'
            }}
          </td>

          <td>
            {{
              item
                .teaching_assignment
                ?.subject
                ?.name ?? '-'
            }}
          </td>

          <td>
            {{
              item
                .teaching_assignment
                ?.teacher
                ?.name ?? '-'
            }}
          </td>
        </tr>

        <tr
          v-if="
            schedules.length === 0
          "
        >
          <td
            colspan="4"
            class="text-center"
          >
            Tidak ada jadwal hari ini
          </td>
        </tr>
      </tbody>
    </v-table>
  </v-card>
</template>