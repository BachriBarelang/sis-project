<script setup>
defineProps({
  modelValue: Boolean,
})

const emit = defineEmits([
  'update:modelValue',
  'confirm',
])
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    max-width="400"
    @update:model-value="emit('update:modelValue', $event)"
  >
    <v-card>

        <v-card-title>
        Hapus Kelas
        </v-card-title>

        <v-card-text>
        Apakah anda yakin ingin menghapus kelas ini?
        </v-card-text>

      <v-card-actions>

        <v-spacer />

        <v-btn
          variant="text"
          @click="emit('update:modelValue', false)"
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