# Hotel Booking

A small full-stack hotel booking application: search → results → checkout → confirmation.

Built by a Senior QA Analyst to understand how every layer of a modern web product works —
frontend, REST API, database, payments, integrations — and to prove it works with automated tests.

> **Status:** work in progress — Phase 1 (search API): search with availability and request validation are done; database-backed search tests, API Resource and Postman collection are next.

## Architecture

```
React (TS) ──HTTP/JSON──> nginx → php-fpm → Laravel API ──> MySQL
                                              ├──> Fake hotel supplier (normal / slow / error / timeout)
                                              ├──> Stripe (test mode) + webhooks
                                              ├──> Queue: confirmation email
                                              └──> MongoDB (raw supplier responses, search logs)
```

## Tech stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.4 |
| Database | MySQL 8.4, MongoDB |
| Frontend | React + TypeScript (Vite) |
| Payments | Stripe (test mode) |
| Tests | Pest (backend), Playwright + TypeScript + Zod (API and E2E) |
| CI | GitHub Actions |

## Scope

- **Search:** city, dates, number of guests
- **Results:** room types with nightly and total price
- **Checkout:** guest details, rate plan, test card payment
- **Confirmation:** booking reference, status, cancellation policy

Out of scope: user accounts, real suppliers, multiple currencies, admin panel.

Business rules: [docs/DOMAIN.md](docs/DOMAIN.md) · Payment testing: [docs/STRIPE_TESTING.md](docs/STRIPE_TESTING.md) · QA: [docs/qa](docs/qa/README.md)

## Roadmap

- [x] Phase 0 — Environment, repository, request path
- [ ] Phase 1 — Data model and search API *(in progress)*
  - [x] Data model and migrations: hotels, room types, nightly inventory (foreign keys, unique night per room type, star rating check)
  - [x] Eloquent models and relations, factories and seeded data (30 hotels, 90 days of inventory)
  - [x] `GET /api/hotels` — availability over a date range (every night must have rooms) with total price in cents
  - [x] Request validation — 422 with field errors for city, dates and guests
  - [x] 17 Pest feature tests for validation; QA test strategy and case catalog in [docs/qa](docs/qa/README.md)
  - [ ] Search tests with a test database, API Resource for a stable response shape, Postman collection
- [ ] Phase 2 — Booking API (validation, conflicts, last-room locking)
- [ ] Phase 3 — React frontend
- [ ] Phase 4 — Payments and cancellation (idempotency, webhooks, refunds)
- [ ] Phase 5 — Failure modes, logs, queues, MongoDB
- [ ] Phase 6 — Automated tests and CI
- [ ] Phase 7 — Deployment and documentation
