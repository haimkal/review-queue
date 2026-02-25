<script setup>
import { ref, onMounted, watch } from 'vue';
import AppHeader from './components/AppHeader.vue';
import HeroSection from './components/HeroSection.vue';
import QueueFeed from './components/QueueFeed.vue';
import CreateItemForm from './components/CreateItemForm.vue';
import { ItemService } from './services/item.service';

const items = ref([]);
const loading = ref(false);
const error = ref(null);
const mode = ref('reviewer');

const stateFilter = ref('');
const q = ref('');
const sort = ref('created_at');
const dir = ref('desc');

function onReviewed(updatedItem) {
  const i = items.value.findIndex((x) => x.id === updatedItem.id);
  if (i !== -1) items.value[i] = updatedItem;

  if (stateFilter.value && updatedItem.state !== stateFilter.value) {
    items.value = items.value.filter((x) => x.id !== updatedItem.id);
  }
}

function onCreated(newItem) {
  if (!newItem?.id) return;

  const item = { ...newItem, state: newItem.state ?? 'pending' };

  const stateOk = !stateFilter.value || item.state === stateFilter.value;
  const simpleCase = !q.value?.trim();
  if (stateOk && simpleCase) {
    items.value.unshift(item);
  } else {
    fetchItems();
  }
}

async function fetchItems() {
  loading.value = true;
  error.value = null;
  try {
    items.value = await ItemService.list({
      state: stateFilter.value || undefined,
      q: q.value || undefined,
      sort: sort.value,
      dir: dir.value,
    });
  } catch (e) {
    console.error(e);
    error.value = 'Failed to load items';
  } finally {
    loading.value = false;
  }
}

onMounted(fetchItems);
watch([stateFilter, sort, dir], fetchItems);
</script>

<template>
  <div class="page">
    <AppHeader :loading="loading" :mode="mode" @update:mode="mode = $event" @refresh="fetchItems" />
    <HeroSection :mode="mode" />

    <main class="layout">
      <QueueFeed
        :items="items"
        :loading="loading"
        :mode="mode"
        :error="error"
        v-model:stateFilter="stateFilter"
        v-model:q="q"
        v-model:sort="sort"
        v-model:dir="dir"
        @refresh="fetchItems"
        @reviewed="onReviewed"
      />

      <aside class="col side">
        <div class="card sideCard">
          <div class="cardTitle sideCardTitle">Submit new item</div>
          <div class="cardSub">Add something to the queue for review.</div>
          <div class="spacer"></div>
          <CreateItemForm @created="onCreated" />
        </div>
      </aside>
    </main>
  </div>
</template>

<style scoped>
.page {
  min-height: 100vh;
  padding-top: 64px;
  background: #fbf6ea;
  color: #0b2440;
  font-family:
    system-ui,
    -apple-system,
    Segoe UI,
    Roboto,
    Arial,
    sans-serif;
}

.layout {
  padding: 14px 32px 36px;
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 18px;
  align-items: start;
}

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

.sideCard {
  padding: 22px 24px;
  position: sticky;
  top: 86px;
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

.sideCardTitle {
  color: #6b4c9a;
  font-weight: 900;
}

.spacer {
  height: 10px;
}

.sideCard :deep(input),
.sideCard :deep(select),
.sideCard :deep(textarea) {
  border-radius: 10px;
  border: 1px solid rgba(11, 36, 64, 0.15);
  padding: 10px 12px;
  font-size: 14px;
}
.sideCard :deep(input:focus),
.sideCard :deep(select:focus),
.sideCard :deep(textarea:focus) {
  outline: none;
  border-color: #008c9b;
  box-shadow: 0 0 0 2px rgba(0, 140, 155, 0.2);
}
.sideCard :deep(button[type='submit']),
.sideCard :deep(button:not(.ghost):not([disabled])) {
  background: #008c9b;
  color: #fff;
  border: 0;
  padding: 10px 20px;
  border-radius: 999px;
  font-weight: 700;
}

@media (max-width: 980px) {
  .layout {
    grid-template-columns: 1fr;
  }
  .sideCard {
    position: static;
  }
}
</style>
