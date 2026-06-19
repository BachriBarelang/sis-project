<script setup>
defineProps({
  title: {
    type: String,
    default: '',
  },

  headers: {
    type: Array,
    default: () => [],
  },

  items: {
    type: Array,
    default: () => [],
  },

  loading: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <v-card>

    <v-card-title
      class="d-flex justify-space-between align-center"
    >
      <span>
        {{ title }}
      </span>

      <slot name="actions" />
    </v-card-title>

    <v-divider />

    <v-data-table
      :headers="headers"
      :items="items"
      :loading="loading"
    >
      <template
        v-for="(_, slotName) in $slots"
        #[slotName]="slotProps"
      >
        <slot
          :name="slotName"
          v-bind="slotProps"
        />
      </template>
    </v-data-table>

  </v-card>
</template>