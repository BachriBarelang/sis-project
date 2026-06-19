<script setup>
const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: 'Hapus Data',
  },
  message: {
    type: String,
    default:
      'Yakin ingin menghapus data ini?',
  },
})

const emit = defineEmits([
  'update:modelValue',
  'confirm',
])

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    max-width="450"
    @update:model-value="
      emit('update:modelValue', $event)
    "
  >
    <v-card>

      <v-card-title>
        {{ title }}
      </v-card-title>

      <v-card-text>
        {{ message }}
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
          @click="emit('confirm')"
        >
          Hapus
        </v-btn>

      </v-card-actions>

    </v-card>
  </v-dialog>
</template>