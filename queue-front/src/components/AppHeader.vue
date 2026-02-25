<script setup>
import BaseButton from './BaseButton.vue';

defineProps({
  loading: Boolean,
  mode: { type: String, default: 'reviewer' },
});
defineEmits(['refresh', 'update:mode']);
</script>

<template>
  <header class="topbar">
    <div class="brandWrap">
      <span class="brand">Review Queue</span>
      <span class="author">by Haim Kalvo</span>
    </div>
    <div class="topbarActions">
      <BaseButton variant="primary" :disabled="loading" @click="$emit('refresh')">
        Refresh
      </BaseButton>
      <div class="seg">
        <BaseButton variant="seg" :active="mode === 'user'" @click="$emit('update:mode', 'user')">
          User
        </BaseButton>
        <BaseButton
          variant="seg"
          :active="mode === 'reviewer'"
          @click="$emit('update:mode', 'reviewer')"
        >
          Reviewer
        </BaseButton>
      </div>
    </div>
  </header>
</template>

<style scoped>
.topbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(11, 36, 64, 0.08);
  z-index: 10;
}

.brandWrap {
  display: flex;
  align-items: baseline;
  gap: 10px;
}
.brand {
  font-weight: 800;
  letter-spacing: -0.02em;
  font-size: 18px;
}
.author {
  font-size: 14px;
  font-weight: 600;
  color: rgba(11, 36, 64, 0.6);
}

.topbarActions {
  display: flex;
  gap: 10px;
}

.seg {
  display: inline-flex;
  background: rgba(11, 36, 64, 0.06);
  border: 1px solid rgba(11, 36, 64, 0.12);
  border-radius: 999px;
  padding: 6px;
  gap: 6px;
}
</style>
