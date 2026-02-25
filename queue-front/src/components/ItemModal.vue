<script setup>
import { ref, watch } from 'vue';
import { ItemService } from '../services/item.service';
import BaseButton from './BaseButton.vue';

const props = defineProps({
  open: Boolean,
  item: Object,
});

const emit = defineEmits(['close', 'reviewed']);

const note = ref('');
const submitting = ref(false);
const error = ref(null);

watch(
  () => props.open,
  (isOpen) => {
    if (isOpen) {
      note.value = props.item?.review_note || '';
      error.value = null;
    }
  }
);

function suggestedAction() {
  return (props.item?.risk_score ?? 0) >= 60 ? 'reject' : 'approve';
}

function onBackdropClick(e) {
  if (e.target === e.currentTarget) emit('close');
}

async function submitReview(action) {
  if (!props.item?.id) return;

  submitting.value = true;
  error.value = null;

  try {
    const updatedItem = await ItemService.review(props.item.id, {
      action,
      note: note.value || null,
    });

    emit('reviewed', updatedItem);
    emit('close');
  } catch (e) {
    console.error(e);
    error.value = e?.response?.data?.message || 'Failed to review item';
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <div v-if="open" class="backdrop" @click="onBackdropClick">
    <div class="modal" role="dialog" aria-modal="true">
      <div class="top">
        <div>
          <div class="title">{{ item?.title }}</div>
          <div class="sub">
            State:
            <span class="state" :class="item?.state">{{ item?.state }}</span>
            <span class="dot">•</span>
            Risk: <b>{{ item?.risk_score }}</b>
            <span class="dot">•</span>
            Suggested:
            <span class="suggestBadge" :class="suggestedAction()">{{ suggestedAction() }}</span>
          </div>
        </div>

        <button class="x" @click="$emit('close')" aria-label="Close">✕</button>
      </div>

      <div class="content">
        <p class="body">{{ item?.content }}</p>

        <div v-if="item?.flags?.length" class="flags">
          <span v-for="f in item.flags" :key="f" class="flag">{{ f }}</span>
        </div>

        <label class="label">
          <span>Review note (optional)</span>
          <textarea v-model="note" rows="3" placeholder="Add a note..." />
        </label>

        <p v-if="error" class="error">{{ error }}</p>
      </div>

      <div class="actions">
        <BaseButton variant="ghost" :disabled="submitting" @click="$emit('close')">
          Cancel
        </BaseButton>

        <div class="right">
          <BaseButton variant="danger" :disabled="submitting" @click="submitReview('reject')">
            Reject
          </BaseButton>
          <BaseButton variant="primary" :disabled="submitting" @click="submitReview('approve')">
            Approve
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.backdrop {
  position: fixed;
  inset: 0;
  background: rgba(11, 36, 64, 0.45);
  display: grid;
  place-items: center;
  padding: 18px;
  z-index: 50;
}

.modal {
  width: min(720px, 100%);
  background: rgba(255, 255, 255, 0.98);
  border: 1px solid rgba(11, 36, 64, 0.12);
  border-radius: 22px;
  box-shadow: 0 20px 60px rgba(11, 36, 64, 0.22);
  overflow: hidden;
}

.top {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  padding: 16px 16px 10px;
  border-bottom: 1px solid rgba(11, 36, 64, 0.08);
}

.title {
  font-weight: 900;
  letter-spacing: -0.01em;
  font-size: 18px;
}

.sub {
  margin-top: 4px;
  font-size: 13px;
  color: rgba(11, 36, 64, 0.7);
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.dot {
  opacity: 0.5;
}

.x {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  border: 1px solid rgba(11, 36, 64, 0.14);
  background: rgba(11, 36, 64, 0.04);
  border-radius: 999px;
  width: 34px;
  height: 34px;
  font-weight: 900;
  cursor: pointer;
  line-height: 1;
}

.content {
  padding: 14px 16px;
}

.body {
  margin: 0;
  color: rgba(11, 36, 64, 0.8);
  line-height: 1.6;
  white-space: pre-wrap;
}

.flags {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
  margin-top: 12px;
}

.flag {
  font-size: 11px;
  font-weight: 800;
  padding: 5px 8px;
  border-radius: 999px;
  background: rgba(11, 36, 64, 0.05);
  border: 1px solid rgba(11, 36, 64, 0.1);
  color: rgba(11, 36, 64, 0.75);
}

.label {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 14px;
}

.label span {
  font-size: 13px;
  font-weight: 800;
  color: rgba(11, 36, 64, 0.75);
}

textarea {
  border: 1px solid rgba(11, 36, 64, 0.18);
  border-radius: 14px;
  padding: 10px 12px;
  font: inherit;
  resize: vertical;
}

.error {
  margin-top: 10px;
  color: #8a1f1f;
  font-weight: 800;
  font-size: 13px;
}

.actions {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  padding: 14px 16px 16px;
  border-top: 1px solid rgba(11, 36, 64, 0.08);
}

.right {
  display: flex;
  gap: 10px;
}

button {
  border: 0;
  padding: 10px 14px;
  border-radius: 999px;
  font-weight: 900;
  cursor: pointer;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.state {
  font-weight: 900;
  text-transform: capitalize;
  padding: 4px 8px;
  border-radius: 999px;
  border: 1px solid rgba(11, 36, 64, 0.12);
  background: rgba(11, 36, 64, 0.05);
}

.state.pending {
  background: rgba(255, 176, 59, 0.12);
  border-color: rgba(255, 176, 59, 0.22);
  color: #7a4b00;
}
.state.approved {
  background: rgba(0, 140, 155, 0.12);
  border-color: rgba(0, 140, 155, 0.2);
  color: #005c66;
}
.state.rejected {
  background: rgba(210, 65, 65, 0.12);
  border-color: rgba(210, 65, 65, 0.22);
  color: #8a1f1f;
}

.suggestBadge {
  font-size: 12px;
  font-weight: 900;
  padding: 4px 10px;
  border-radius: 999px;
  border: 1px solid rgba(11, 36, 64, 0.12);
  text-transform: capitalize;
}

.suggestBadge.approve {
  background: rgba(0, 140, 155, 0.12);
  border-color: rgba(0, 140, 155, 0.2);
  color: #005c66;
}

.suggestBadge.reject {
  background: rgba(210, 65, 65, 0.12);
  border-color: rgba(210, 65, 65, 0.22);
  color: #8a1f1f;
}
</style>
