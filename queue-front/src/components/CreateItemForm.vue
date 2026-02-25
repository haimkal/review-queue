<script setup>
import { ref } from 'vue';
import { ItemService } from '../services/item.service';
import BaseButton from './BaseButton.vue';

const emit = defineEmits(['created']);

const title = ref('');
const content = ref('');
const submitting = ref(false);
const error = ref(null);

async function submit() {
  error.value = null;

  if (!title.value.trim() || !content.value.trim()) {
    error.value = 'Title and content are required';
    return;
  }

  submitting.value = true;
  try {
    const data = await ItemService.create({
      title: title.value,
      content: content.value,
    });

    emit('created', data?.item);
    title.value = '';
    content.value = '';
  } catch (e) {
    console.error(e);
    const msg = e?.response?.data?.message || 'Failed to create item';
    error.value = msg;
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <section style="border: 1px solid #ddd; padding: 12px; margin: 16px 0">
    <h2 style="margin: 0 0 10px">Submit item</h2>

    <p v-if="error" style="color: red; margin: 0 0 10px">{{ error }}</p>

    <div style="display: grid; gap: 10px">
      <label style="display: flex; flex-direction: column; gap: 6px">
        <span>Title</span>
        <input v-model="title" placeholder="e.g. Short post title" />
      </label>

      <label style="display: flex; flex-direction: column; gap: 6px">
        <span>Content</span>
        <textarea v-model="content" rows="4" placeholder="Write your item..." />
      </label>

      <div style="display: flex; gap: 10px; align-items: center">
        <BaseButton variant="primary" :disabled="submitting" @click="submit">
          {{ submitting ? 'Submitting...' : 'Submit' }}
        </BaseButton>
      </div>
    </div>
  </section>
</template>
