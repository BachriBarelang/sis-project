<script setup>
defineProps({
  stats: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <div class="stats-grid">

    <div class="stat-card">
      <div class="icon">👨‍🎓</div>
      <div class="content">
        <div class="number">{{ stats.students }}</div>
        <div class="label">Students</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon">👨‍🏫</div>
      <div class="content">
        <div class="number">{{ stats.teachers }}</div>
        <div class="label">Teachers</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon">🏫</div>
      <div class="content">
        <div class="number">{{ stats.classes }}</div>
        <div class="label">Classes</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon">📚</div>
      <div class="content">
        <div class="number">{{ stats.subjects }}</div>
        <div class="label">Subjects</div>
      </div>
    </div>

  </div>
</template>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-top: 10px;
}

/* CARD */
.stat-card {
  background: rgb(var(--v-theme-surface));
  color: rgb(var(--v-theme-on-surface));
  border-radius: 16px;
  padding: 16px;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border: 1px solid rgba(0,0,0,0.05);
  transition: all 0.25s ease;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* ICON */
.icon {
  font-size: 28px;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(99,102,241,0.1);
  border-radius: 12px;
}

/* TEXT */
.number {
  font-size: 20px;
  font-weight: bold;
  color: rgb(var(--v-theme-on-surface));
}

.label {
  font-size: 13px;
  color: rgba(var(--v-theme-on-surface), 0.6);
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>