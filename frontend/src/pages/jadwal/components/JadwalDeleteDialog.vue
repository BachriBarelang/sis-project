<script setup>
import api from '@/plugins/axios'

const props = defineProps({
  modelValue: Boolean,
  item: Object,
})

const emit = defineEmits([
  'update:modelValue',
  'deleted',
])

const close = () => {
  emit('update:modelValue', false)
}

const remove = async () => {
  await api.delete(`/schedules/${props.item.id}`)

  emit('deleted')
  close()
}
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    max-width="400"
    @update:model-value="emit('update:modelValue', $event)"
  >
    <v-card>
      <v-card-title>
        Hapus Jadwal
      </v-card-title>

      <v-card-text>
        Yakin ingin menghapus jadwal ini?
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
          color="error"
          @click="remove"
        >
          Hapus
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>