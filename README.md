# Review Queue

A full-stack moderation system built with:

- Laravel (backend API)
- Vue 3 + Vite (frontend)

The system allows submitting items to a queue and reviewing them using a simple rule-based moderation heuristic.

---

## 🚀 Quick Start

### 1️⃣ Backend

```bash
cd queue-back
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

Runs at: http://localhost:8000

### 2️⃣ Frontend

```bash
cd queue-front
npm install
npm run dev
```

Runs at: http://localhost:5173

---

## 🧠 Features

- Submit items (User mode)
- Review items (Reviewer mode)
- Risk scoring heuristic
- Filtering, sorting, search
- Optimistic UI updates
- Backend + Frontend tests

---

## 📁 Project Structure

- `/queue-back` – Laravel API
- `/queue-front` – Vue frontend

## 🧪 Running Tests

Backend:
php artisan test

Frontend:
npm run test

See individual READMEs inside each folder for more details.

---

Built by Haim Kalvo
