<script setup>
defineProps({
  label: String,
  modelValue: String,
  options: {
    type: Array,
    required: true,
  },
});

defineEmits(['update:modelValue']);
</script>

<template>
  <label style="display: flex; flex-direction: column; gap: 6px">
    <span>{{ label }}</span>
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value)">
      <option v-for="opt in options" :key="opt.value" :value="opt.value">
        {{ opt.label }}
      </option>
    </select>
  </label>
</template>
