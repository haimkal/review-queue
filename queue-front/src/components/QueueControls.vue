<script setup>
import BaseSelect from './BaseSelect.vue';
import BaseButton from './BaseButton.vue';

defineProps({
  stateFilter: String,
  q: String,
  sort: String,
  dir: String,
  loading: Boolean,
});

defineEmits(['update:stateFilter', 'update:q', 'update:sort', 'update:dir', 'refresh']);
</script>

<template>
  <div style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap; margin: 16px 0">
    <BaseSelect
      label="State"
      :modelValue="stateFilter"
      @update:modelValue="$emit('update:stateFilter', $event)"
      :options="[
        { label: 'All', value: '' },
        { label: 'Pending', value: 'pending' },
        { label: 'Approved', value: 'approved' },
        { label: 'Rejected', value: 'rejected' },
      ]"
    />

    <label style="display: flex; flex-direction: column; gap: 6px">
      <span>Search</span>
      <input
        :value="q"
        @input="$emit('update:q', $event.target.value)"
        @keydown.enter="$emit('refresh')"
        placeholder="title / content"
      />
    </label>

    <BaseSelect
      label="Sort"
      :modelValue="sort"
      @update:modelValue="$emit('update:sort', $event)"
      :options="[
        { label: 'Created', value: 'created_at' },
        { label: 'Risk', value: 'risk_score' },
      ]"
    />

    <BaseSelect
      label="Direction"
      :modelValue="dir"
      @update:modelValue="$emit('update:dir', $event)"
      :options="[
        { label: 'Desc', value: 'desc' },
        { label: 'Asc', value: 'asc' },
      ]"
    />

    <BaseButton variant="secondary" :disabled="loading" @click="$emit('refresh')">
      Refresh
    </BaseButton>
  </div>
</template>
