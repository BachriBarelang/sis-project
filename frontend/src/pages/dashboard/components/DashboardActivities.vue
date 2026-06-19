<script setup>
import { computed } from 'vue'

const props = defineProps({
  activities: {
    type: Array,
    default: () => [],
  },
})

const limitedActivities = computed(() =>
  props.activities.slice(0, 5)
)
</script>

<template>
  <div class="card">
    <h3 class="title">Recent Activities</h3>

    <div v-if="limitedActivities.length === 0" class="empty">
      No recent activity
    </div>

    <div v-else class="timeline">
      <div
        v-for="(item, index) in limitedActivities"
        :key="index"
        class="activity-item"
      >
        <div class="message">
          {{ item.message }}
        </div>

        <div class="time">
          {{ item.created_at }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
  color: rgb(var(--v-theme-on-surface));
}

/* EMPTY */
.empty {
  font-size: 13px;
  color: rgba(var(--v-theme-on-surface), 0.6);
}

/* TIMELINE */
.timeline {
  position: relative;
  padding-left: 16px;
}

.timeline::before {
  content: "";
  position: absolute;
  left: 6px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: rgba(var(--v-theme-on-surface), 0.1);
}

.timeline-item {
  display: flex;
  gap: 10px;
  margin-bottom: 14px;
}

.dot {
  width: 10px;
  height: 10px;
  background: #4f46e5;
  border-radius: 50%;
  margin-top: 4px;
}

/* CONTENT CARD */
.card{
   background: rgb(var(--v-theme-surface));
}

.content {
  flex: 1;
  background: rgb(var(--v-theme-surface));
  padding: 10px 12px;
  border-radius: 12px;
  border: 1px solid rgba(var(--v-theme-on-surface), 0.08);
  transition: all 0.25s ease;
}

.content:hover {
  transform: translateX(3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

/* TEXT */
.message {
  font-size: 13px;
  font-weight: 500;
  color: rgb(var(--v-theme-on-surface));
}

.time {
  font-size: 11px;
  color: rgba(var(--v-theme-on-surface), 0.6);
  margin-top: 4px;
}

.activity-item {
  padding: 12px;
  border-radius: 12px;
  margin-bottom: 10px;
  background: rgb(var(--v-theme-surface));
  border: 1px solid rgba(var(--v-theme-on-surface), 0.08);
  transition: all .25s ease;
}

.activity-item:hover {
  transform: translateX(4px);
}

.message {
  font-size: 13px;
  font-weight: 500;
}

.time {
  margin-top: 4px;
  font-size: 11px;
  opacity: .6;
}
</style>