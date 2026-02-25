# Review Queue – Frontend (Vue 3)

This is the frontend for the Review Queue moderation system.

It provides:

- User Mode (submit items)
- Reviewer Mode (review and moderate items)
- Filtering, searching, and sorting
- Optimistic UI updates
- Unit-tested reusable components

---

## 🛠 Tech Stack

- Vue 3 (Composition API)
- Vite
- Axios
- Vitest
- @vue/test-utils

---

## 🚀 Setup Instructions

### Requirements

- Node 18+

### Installation

```bash
npm install
npm run dev
```

Frontend runs at:

```
http://localhost:5173
```

Make sure the backend is running at:

```
http://localhost:8000
```

---

## 🧩 Architecture

### Services Layer

All API calls are centralized inside:

```
src/services/item.service.js
```

This keeps components clean and separates UI from data access logic.

---

### Component Structure

Reusable components:

- BaseButton
- BaseSelect

Feature components:

- AppHeader
- HeroSection
- QueueFeed
- QueueControls
- ItemModal
- CreateItemForm

---

## 🔄 Modes

### User Mode

- Submit items to the queue
- Simplified interface

### Reviewer Mode

- View flags and risk scores
- Filter by state
- Search content
- Sort by risk
- Approve / Reject items
- Add review notes

---

## ⚡ Optimistic Updates

Review actions update the UI immediately without refetching the entire list.

This avoids unnecessary API calls and improves responsiveness.

---

## 🧪 Testing

Unit tests cover:

- BaseSelect
- BaseButton
- QueueControls
- ItemModal (including async review behavior)

Run tests:

```bash
npm run test        # watch mode
npm run test:run    # single run (e.g. for CI)
```

---

## ⚖️ Design Tradeoffs

Optimized for:

- Clear state flow
- Component reusability
- Separation of concerns
- Clean UX

---

## 🔮 Possible Improvements

- Add role-based UI enforcement
- Add animations for optimistic updates
- Add E2E tests (Playwright)
- Improve accessibility
