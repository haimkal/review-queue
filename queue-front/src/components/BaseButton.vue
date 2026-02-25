<script setup>
const props = defineProps({
  variant: { type: String, default: 'primary' }, // primary | secondary | ghost | danger | seg
  disabled: Boolean,
  active: Boolean,
  type: { type: String, default: 'button' },
});

defineEmits(['click']);
</script>

<template>
  <button
    :type="type"
    class="btn"
    :class="[variant, { active }]"
    :disabled="disabled"
    @click="$emit('click', $event)"
  >
    <slot />
  </button>
</template>

<style scoped>
.btn {
  border: 0;
  cursor: pointer;
  font-weight: 800;
  border-radius: 999px;
  padding: 10px 14px;
}
.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.primary {
  background: #008c9b;
  color: #fff;
}
.primary:hover:not(:disabled) {
  background: #006d79;
}
.secondary {
  background: #fff;
  border: 1px solid rgba(11, 36, 64, 0.2);
  color: #0b2440;
}
.secondary:hover:not(:disabled) {
  background: #f5f5f7;
}
.ghost {
  background: transparent;
  border: 1px solid rgba(11, 36, 64, 0.15);
}
.danger {
  background: rgba(210, 65, 65, 0.14);
  border: 1px solid rgba(210, 65, 65, 0.22);
  color: #8a1f1f;
}

.seg {
  background: transparent;
  padding: 10px 20px;
  color: rgba(11, 36, 64, 0.5);
}
.seg.active {
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 6px 18px rgba(11, 36, 64, 0.1);
  color: #0b2440;
}
</style>
