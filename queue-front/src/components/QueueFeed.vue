<script setup>
import { ref } from 'vue';
import QueueControls from './QueueControls.vue';
import ItemModal from './ItemModal.vue';

const props = defineProps({
  items: { type: Array, default: () => [] },
  loading: Boolean,
  error: { type: String, default: null },
  stateFilter: { type: String, default: '' },
  q: { type: String, default: '' },
  sort: { type: String, default: 'created_at' },
  dir: { type: String, default: 'desc' },
  mode: { type: String, default: 'reviewer' },
});

const emit = defineEmits([
  'update:stateFilter',
  'update:q',
  'update:sort',
  'update:dir',
  'refresh',
  'reviewed',
]);

const selectedItem = ref(null);

function openItem(item) {
  if (props.mode !== 'reviewer') return;
  selectedItem.value = item;
}

function suggestedAction(item) {
  return (item?.risk_score ?? 0) >= 60 ? 'reject' : 'approve';
}

function handleReviewed(updatedItem) {
  selectedItem.value = null;
  emit('reviewed', updatedItem);
}
</script>

<template>
  <section class="col">
    <div v-if="props.mode === 'reviewer'" class="card toolbarCard">
      <QueueControls
        :stateFilter="stateFilter"
        :q="q"
        :sort="sort"
        :dir="dir"
        :loading="loading"
        @update:stateFilter="$emit('update:stateFilter', $event)"
        @update:q="$emit('update:q', $event)"
        @update:sort="$emit('update:sort', $event)"
        @update:dir="$emit('update:dir', $event)"
        @refresh="$emit('refresh')"
      />
    </div>

    <div class="card listCard">
      <div class="cardHeader">
        <div>
          <div class="cardTitle listCardTitle">Queue</div>
          <div class="cardSub">
            Showing <b>{{ items.length }}</b> item<span v-if="items.length !== 1">s</span>
          </div>
        </div>

        <div class="status">
          <span v-if="loading" class="badge">Loading…</span>
          <span v-else-if="error" class="badge error">{{ error }}</span>
          <span v-else class="badge ok">Up to date</span>
        </div>
      </div>

      <div v-if="!loading && !error && items.length === 0" class="empty">
        No items yet. Submit one on the right 🙂
      </div>

      <div class="list">
        <article
          :style="{ cursor: props.mode === 'reviewer' ? 'pointer' : 'default' }"
          v-for="item in items"
          :key="item.id"
          class="item"
          role="button"
          tabindex="0"
          @click="openItem(item)"
          @keydown.enter="openItem(item)"
          @keydown.space.prevent="openItem(item)"
        >
          <div class="itemTop">
            <h3 class="itemTitle">{{ item.title }}</h3>
            <span class="state" :class="item.state">{{ item.state }}</span>
          </div>

          <p class="itemBody">{{ item.content }}</p>

          <div class="itemMeta">
            <span v-if="props.mode === 'reviewer'" class="meta">
              Suggested:
              <b :class="suggestedAction(item)">{{ suggestedAction(item) }}</b>
            </span>

            <span v-if="props.mode === 'reviewer' && item.flags?.length" class="flags">
              <span v-for="f in item.flags" :key="f" class="flag">{{ f }}</span>
            </span>
          </div>
        </article>
      </div>
    </div>

    <ItemModal
      :open="!!selectedItem"
      :item="selectedItem"
      @close="selectedItem = null"
      @reviewed="handleReviewed"
    />
  </section>
</template>

<style scoped>
.col {
  display: grid;
  gap: 14px;
}

.card {
  background: rgba(255, 255, 255, 0.96);
  border: 1px solid rgba(11, 36, 64, 0.08);
  border-radius: 24px;
  box-shadow: 0 12px 40px rgba(11, 36, 64, 0.06);
}

.toolbarCard {
  padding: 10px 12px;
}

.listCard {
  padding: 14px;
}

.listCardTitle {
  color: #008c9b;
  font-weight: 900;
}

.cardHeader {
  display: flex;
  align-items: start;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 10px;
}

.cardTitle {
  font-weight: 900;
  font-size: 18px;
  letter-spacing: -0.01em;
}

.cardSub {
  color: rgba(11, 36, 64, 0.65);
  font-size: 13px;
  margin-top: 2px;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 800;
  border: 1px solid rgba(11, 36, 64, 0.12);
  background: rgba(11, 36, 64, 0.04);
}

.badge.ok {
  background: rgba(0, 140, 155, 0.1);
  border-color: rgba(0, 140, 155, 0.18);
  color: #005c66;
}

.badge.error {
  background: rgba(210, 65, 65, 0.1);
  border-color: rgba(210, 65, 65, 0.2);
  color: #8a1f1f;
}

.list {
  display: grid;
  gap: 12px;
}

.item {
  padding: 14px;
  border-radius: 18px;
  border: 1px solid rgba(11, 36, 64, 0.1);
  background: rgba(255, 255, 255, 0.95);
  cursor: pointer;
}
.item:hover {
  background: rgba(255, 255, 255, 1);
  border-color: rgba(11, 36, 64, 0.16);
}

.itemTop {
  display: flex;
  align-items: start;
  justify-content: space-between;
  gap: 10px;
}

.itemTitle {
  margin: 0;
  font-size: 16px;
  letter-spacing: -0.01em;
}

.state {
  font-size: 12px;
  font-weight: 900;
  padding: 6px 10px;
  border-radius: 999px;
  border: 1px solid rgba(11, 36, 64, 0.12);
  background: rgba(11, 36, 64, 0.05);
  text-transform: capitalize;
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

.itemBody {
  margin: 10px 0 0;
  color: rgba(11, 36, 64, 0.78);
  line-height: 1.55;
  white-space: pre-wrap;
}

.itemMeta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
  margin-top: 12px;
  flex-wrap: wrap;
}

.flags {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
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

.empty {
  padding: 18px;
  border-radius: 18px;
  border: 1px dashed rgba(11, 36, 64, 0.2);
  color: rgba(11, 36, 64, 0.65);
  background: rgba(255, 255, 255, 0.6);
}
</style>

<style scoped>
.toolbarCard :deep(input),
.toolbarCard :deep(select) {
  border-radius: 10px;
  border: 1px solid rgba(11, 36, 64, 0.15);
}
.toolbarCard :deep(button) {
  border-radius: 999px;
  font-weight: 700;
}

.approve {
  color: #005c66;
}

.reject {
  color: #8a1f1f;
}
</style>
