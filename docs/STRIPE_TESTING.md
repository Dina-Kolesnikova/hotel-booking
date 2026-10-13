# STRIPE_TESTING.md — payments in test mode (phase 4)

No real money ever moves. Test keys start with `pk_test_` / `sk_test_` and live only in `backend/.env`.

## Setup
- Free account at https://dashboard.stripe.com (test mode is available without business verification).
- SDK: `composer require stripe/stripe-php` (plain SDK, not Cashier — we want to see every call).
- CLI: `brew install stripe/stripe-cli/stripe`, then `stripe login`.
- Webhooks to local app: `stripe listen --forward-to http://hotel-api.test/api/stripe/webhook`
  (copy the `whsec_...` secret it prints into `.env`).
- Fire a test event: `stripe trigger payment_intent.succeeded`.

## Test cards (any future expiry, any CVC, any postal code)
| Card | Result |
|---|---|
| 4242 4242 4242 4242 | Success |
| 4000 0000 0000 0002 | Declined |
| 4000 0000 0000 9995 | Insufficient funds |
| 4000 0025 0000 3155 | Requires 3D Secure confirmation |
Full list: https://docs.stripe.com/testing

## Scenario matrix (fill in Result as you test)
| # | Scenario | Expected | Result |
|---|---|---|---|
| P1 | Pay with 4242, non-refundable | booking `confirmed` via webhook, rooms held | |
| P2 | Declined card | `payment_failed` or still `pending`; can retry; no confirmation email | |
| P3 | 3DS card, user confirms | `confirmed` | |
| P4 | 3DS card, user cancels | not confirmed; rooms released after 15 min | |
| P5 | Double click "Pay" | one PaymentIntent, one charge | |
| P6 | Same webhook delivered twice | booking processed once | |
| P7 | Webhook with wrong signature | rejected (400), nothing changes | |
| P8 | Webhook arrives late (browser already on confirmation page) | page shows "processing", then confirmed | |
| P9 | Refundable rate | card authorized, not captured | |
| P10 | Cancel refundable > 48 h before check-in | authorization released / full refund, rooms released | |
| P11 | Cancel refundable < 48 h | first night charged | |
| P12 | Cancel twice | second cancel rejected, no second refund | |
| P13 | Pending booking not paid in 15 min | expired, rooms released | |
