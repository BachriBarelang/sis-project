<script setup>
import { computed } from 'vue'

const props = defineProps({
  schedules: {
    type: Array,
    default: () => [],
  },
})

const days = [
  'Senin',
  'Selasa',
  'Rabu',
  'Kamis',
  'Jumat',
  'Sabtu',
]

const timeSlots = computed(() => {

  const slots = props.schedules.map(
    item =>
      `${item.start_time} - ${item.end_time}`
  )

  return [...new Set(slots)].sort()
})

const getSchedule = (
  day,
  slot
) => {

  return props.schedules.find(
    item =>
      item.day === day &&
      `${item.start_time} - ${item.end_time}` === slot
  )
}
</script>

<template>
  <v-table
    density="comfortable"
    class="border"
  >

    <thead>

      <tr>
        <th width="150">
          Jam
        </th>

        <th
          v-for="day in days"
          :key="day"
        >
          {{ day }}
        </th>
      </tr>

    </thead>

    <tbody>

      <tr
        v-for="slot in timeSlots"
        :key="slot"
      >

        <td class="font-weight-bold">
          {{ slot }}
        </td>

        <td
          v-for="day in days"
          :key="day"
        >

          <template
            v-if="
              getSchedule(day, slot)
            "
          >

            <div
              class="font-weight-bold"
            >
              {{
                getSchedule(day, slot)
                .teaching_assignment
                ?.subject?.name
              }}
            </div>

            <div
              class="text-caption"
            >
              {{
                getSchedule(day, slot)
                .teaching_assignment
                ?.teacher?.name
              }}
            </div>

          </template>

        </td>

      </tr>

    </tbody>

  </v-table>
</template>