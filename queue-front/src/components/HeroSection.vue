<script setup>
defineProps({
  mode: {
    type: String,
    default: 'reviewer',
  },
});
</script>

<template>
  <section class="hero">
    <div class="heroCurve" aria-hidden="true"></div>
    <div class="heroText">
      <h1 v-if="mode === 'reviewer'">
        Review with <span class="heroHighlight">care</span> and responsibility.
      </h1>
      <h1 v-else>Share your <span class="heroHighlight">thoughts</span> with confidence.</h1>

      <p v-if="mode === 'reviewer'">
        Use filters, risk scores, and heuristics to guide thoughtful moderation decisions.
      </p>
      <p v-else>Create and submit items to the review queue in just a few clicks.</p>

      <div v-if="mode === 'reviewer'" class="heroPills">
        <span class="pill">Queue</span>
        <span class="pill">Heuristic</span>
        <span class="pill">Approve / Reject</span>
      </div>
    </div>

    <div class="heroCard">
      <div class="heroCardTitle">Quick tips</div>
      <ul v-if="mode === 'reviewer'">
        <li><b>Use State</b> + <b>Search</b> to narrow the list.</li>
        <li>Sort by <b>Risk</b> to review high-risk items first.</li>
      </ul>

      <ul v-else>
        <li>Use the form to submit new items to the queue.</li>
      </ul>
    </div>
  </section>
</template>

<style scoped>
.hero {
  padding: 48px 32px 28px;
  display: grid;
  grid-template-columns: 1.3fr 0.7fr;
  gap: 24px;
  position: relative;
  overflow: hidden;
}

.heroCurve {
  position: absolute;
  top: -80px;
  right: -80px;
  width: 420px;
  height: 420px;
  background: linear-gradient(135deg, rgba(0, 140, 155, 0.12) 0%, rgba(0, 140, 155, 0.06) 100%);
  border-radius: 50%;
  pointer-events: none;
}

.heroHighlight {
  border-bottom: 3px dashed rgba(255, 176, 59, 0.9);
  padding-bottom: 2px;
}

.heroText h1 {
  margin: 0;
  font-size: 54px;
  line-height: 1.02;
  letter-spacing: -0.03em;
}

.heroText p {
  margin: 14px 0 0;
  max-width: 620px;
  color: rgba(11, 36, 64, 0.75);
  font-size: 16px;
  line-height: 1.6;
}

.heroPills {
  display: flex;
  gap: 10px;
  margin-top: 16px;
  flex-wrap: wrap;
}

.pill {
  background: rgba(0, 140, 155, 0.12);
  color: #005c66;
  border: 1px solid rgba(0, 140, 155, 0.18);
  padding: 7px 10px;
  border-radius: 999px;
  font-weight: 700;
  font-size: 13px;
}

.heroCard {
  background: rgba(255, 255, 255, 0.95);
  border: 1px solid rgba(11, 36, 64, 0.08);
  border-radius: 24px;
  padding: 22px 24px;
  box-shadow: 0 12px 40px rgba(11, 36, 64, 0.06);
}

.heroCardTitle {
  font-weight: 900;
  margin-bottom: 8px;
}

.heroCard ul {
  margin: 0;
  padding-left: 18px;
  color: rgba(11, 36, 64, 0.8);
  line-height: 1.55;
}

@media (max-width: 980px) {
  .hero {
    grid-template-columns: 1fr;
  }
  .heroText h1 {
    font-size: 40px;
  }
}
</style>
