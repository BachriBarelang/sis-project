<script setup>
defineProps({
  warnings: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <div class="card">
    <h3 class="title">System Warnings</h3>

    <div v-if="warnings.length === 0" class="empty">
      No warnings 🎉
    </div>

    <div v-else class="list">
      <div
        v-for="(item, index) in warnings"
        :key="index"
        class="warning-card"
        :class="item.type"
      >
        <div class="content">
          <div class="message">
            {{ item.message }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* TITLE */
.card{
  background: rgb(var(--v-theme-surface));
  color: rgb(var(--v-theme-on-surface));
}

.title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
  background: rgb(var(--v-theme-surface));
  color: rgb(var(--v-theme-on-surface));
}

/* EMPTY */
.empty {
  font-size: 13px;
  color: rgba(var(--v-theme-on-surface), 0.6);
  padding: 10px 0;
}

/* LIST */
.list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

/* CARD BASE */
.warning-card {
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid ;
  cursor: pointer;
  transition: all 0.25s ease;
  background: rgb(var(--v-theme-surface));
  color: rgb(var(--v-theme-on-surface));
  font-weight: 500;
}

/* HOVER (TEBAL + PREMIUM FEEL) */
.warning-card:hover {
  transform: translateY(-3px);
  font-weight: 700;
  box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* TYPES */
.warning {
  border-left: 4px solid #f59e0b;
}

.warning:hover {
  box-shadow: 0 10px 25px rgba(245, 158, 11, 0.25);
}

.danger {
  border-left: 4px solid #ef4444;
}

.danger:hover {
  box-shadow: 0 10px 25px rgba(239, 68, 68, 0.25);
}

.info {
  border-left: 4px solid #3b82f6;
}

.info:hover {
  box-shadow: 0 10px 25px rgba(59, 130, 246, 0.25);
}
</style>