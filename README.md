# Hotel Booking

A small full-stack hotel booking application: search → results → checkout → confirmation.

Built by a Senior QA Analyst to understand how every layer of a modern web product works —
frontend, REST API, database, payments, integrations — and to prove it works with automated tests.

> **Status:** work in progress — Phase 0 (environment and repository setup).

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

Business rules: [docs/DOMAIN.md](docs/DOMAIN.md) · Payment testing: [docs/STRIPE_TESTING.md](docs/STRIPE_TESTING.md)

## Roadmap

- [ ] Phase 0 — Environment, repository, request path
- [ ] Phase 1 — Data model and search API
- [ ] Phase 2 — Booking API (validation, conflicts, last-room locking)
- [ ] Phase 3 — React frontend
- [ ] Phase 4 — Payments and cancellation (idempotency, webhooks, refunds)
- [ ] Phase 5 — Failure modes, logs, queues, MongoDB
- [ ] Phase 6 — Automated tests and CI
- [ ] Phase 7 — Deployment and documentation
