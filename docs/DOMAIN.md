# DOMAIN.md — hotel booking rules (the "product spec")

Fake product, realistic rules. Use them for design decisions, validation and test cases.

## Search
- Inputs: city, check-in date, check-out date, number of guests.
- Nights = check-out − check-in (check-out day is not a night).
- Check-in ≥ today; check-out > check-in; max stay 30 nights; search window up to 90 days ahead.
- Guests 1–6; a room type is shown only if its capacity ≥ guests.
- A room type is available only if EVERY night of the stay has ≥ 1 room left.
- Show price per night (may differ by night: weekend +20%) and total for the stay.

## Hotels and rooms
- Hotel: name, city, address, star rating (1–5).
- Room type: name (Standard, Deluxe, Suite, Family), capacity, total rooms.
- Nightly inventory: rooms left + price for each room type for each date.

## Rate plans
- **Non-refundable:** 10% cheaper; paid in full at booking.
- **Refundable:** standard price; card is authorized at booking, charged later; free cancellation until 48 h before check-in, after that the first night is charged.

## Price
- Total = sum of nightly prices + taxes 15% + fixed service fee $10 per booking.
- Money stored as integer cents, never floats.
- Currency: CAD only.

## Booking
- Reference: 8 characters, unique, e.g. `HB7K2Q9X`.
- Statuses: `pending` (created, waiting for payment) → `confirmed` (payment ok) / `payment_failed`; `confirmed` → `cancelled`.
- A `pending` booking holds the rooms for 15 minutes; if not paid, it expires and rooms are released.
- Booking reduces availability for every night of the stay; cancellation/expiry releases it.
- Guest details: first name, last name, email (valid format), phone optional.

## Cancellation
- Only `confirmed` bookings can be cancelled.
- Refund follows the rate plan rule above.
- Cancelling twice must not refund twice.

## Supplier (phase 5)
- After payment, the booking is confirmed with the (fake) hotel supplier.
- Supplier can be slow, fail or time out; the booking must end in a clear, explainable state.
