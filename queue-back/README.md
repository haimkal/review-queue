# Review Queue – Backend (Laravel)

This backend provides a REST API for a simple moderation system.

It supports:

- Creating items
- Reviewing items (approve / reject with note)
- Filtering, searching, and sorting items
- A simple rule-based moderation heuristic

---

## 🛠 Tech Stack

- Laravel
- SQLite
- PHPUnit

---

## 🏗 Architecture

- Controllers handle HTTP concerns
- A dedicated HeuristicService encapsulates moderation logic
- Eloquent models manage persistence
- Feature tests validate API behavior

## 🚀 Setup Instructions

### Requirements

- PHP 8.2+
- Composer

### Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create the SQLite database:

```bash
touch database/database.sqlite
```

Update your `.env` file:

```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Run migrations:

```bash
php artisan migrate
```

Start the development server:

```bash
php artisan serve
```

Backend runs at:

```
http://localhost:8000
```

Quick smoke test:

```bash
curl http://localhost:8000/api/health
```

---

## 📡 API Endpoints

### Health

GET `/api/health`

Returns `{"ok": true}`.

---

### Create Item

POST `/api/items`

Body:

```json
{
    "title": "string",
    "content": "string"
}
```

Returns:

- item (created item with id, title, content, state, risk_score, flags, etc.)
- suggested_action (approve | reject)

Example:

```json
{
    "item": {
        "id": 1,
        "title": "Hello",
        "content": "Some valid content here",
        "risk_score": 10,
        "flags": [],
        "created_at": "...",
        "updated_at": "..."
    },
    "suggested_action": "approve"
}
```

---

### List Items

GET `/api/items`

Query parameters:

- state (pending | approved | rejected)
- q (search string)
- sort (created_at | risk_score)
- dir (asc | desc)

Returns an array of item objects.

---

### Review Item

POST `/api/items/{id}/review`

Body:

```json
{
  "action": "approve" | "reject",
  "note": "optional string"
}
```

---

## 🧠 Moderation Heuristic

Each item is evaluated on creation.

Rules include:

- URL detection
- Spam keyword detection
- Excessive punctuation detection
- Short content detection

The heuristic returns:

- risk_score (0–100)
- flags (array)
- suggested_action

The heuristic is implemented as a dedicated service class for extensibility.

---

## 🧪 Testing

Feature tests cover:

- Item creation
- Item review
- Filtering, searching, and sorting

Run tests:

```bash
php artisan test
```

---

## ⚖️ Design Tradeoffs

Optimized for:

- Clarity
- Simplicity
- Separation of concerns
- Testability

Out of scope for the timebox:

- Authentication & role enforcement
- Pagination
- Background processing
- Production-level logging & monitoring

## 🔑 Key Decisions

- **Data model:** a single `items` table with `state`, `review_note`, `reviewed_at`, `risk_score`, and `flags` to keep the domain minimal and easy to extend.
- **API design:** REST-style endpoints (`POST /items`, `GET /items`, `POST /items/{id}/review`) to keep the flow explicit and predictable for the UI.
- **Persistence:** SQLite for zero-config local setup and fast iteration within the timebox.

## ✅ Assumptions

- No authentication is required for this assignment; “User/Reviewer mode” is a UI-level toggle.
- Newly created items start in `pending`.
- The moderation heuristic is intentionally simple and deterministic for easy discussion/modification in the follow-up interview.

---

## 🔮 Possible Improvements

- Add authentication & roles
- Add pagination
- Add indexing for large datasets
- Replace heuristic with ML-based moderation

## ⚠️ Error Handling

- Validation errors return 422 responses.
- Invalid review actions return 400 responses.
- Missing resources return 404.
