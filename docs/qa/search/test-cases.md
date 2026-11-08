# Test cases: hotel search (`GET /api/hotels`)

Scope: query parameters `city`, `check_in`, `check_out`, `guests`.
General process, levels and techniques: [`../test-strategy.md`](../test-strategy.md).

## How to read and keep this file

- **ID** is the key. The same ID is the prefix of the automated test name (`V2: search with empty city ...`), so a case and its test can always be matched.
- **Level**: `L1` = feature test without data (Pest), `L2` = feature test with a database (Pest + factories), `L3` = API test from outside (Postman, later Playwright), `EXP` = exploratory.
- **Method**: `auto` or `manual`.
- **Test** points to the file that holds the test. `-` means not written yet.
- **Status** is `DONE` only when a test with that ID exists **and passes**. Do not edit it by memory. Check with:

```bash
cd backend
php artisan test --compact tests/Feature/HotelSearchValidationTest.php      # does everything pass?
grep -o "test('[A-Z][0-9a-z]*:" tests/Feature/HotelSearchValidationTest.php  # which IDs exist as tests?
```

Status values: `DONE`, `TODO` (agreed, not written), `OPEN` (needs a decision first).

## 1. Validation cases (L1, auto)

| ID | Case | Expected | Technique | Test | Status |
|---|---|---|---|---|---|
| H1 | All four fields valid (baseline) | no validation error on any field | Equivalence | HotelSearchValidationTest | DONE |
| V1 | All four fields missing | 422, errors for all four | Equivalence | HotelSearchValidationTest | DONE |
| V2 | `city=` (empty) | 422 on `city` | Equivalence | HotelSearchValidationTest | DONE |
| V3 | `city` is exactly 100 characters | no error on `city` | Boundary | HotelSearchValidationTest | DONE |
| V4 | `city` is 101 characters | 422 on `city` | Boundary | HotelSearchValidationTest | DONE |
| V5 | `city=Київ` (non-Latin) | no error on `city` | Error guessing | HotelSearchValidationTest | DONE |
| V6 | `guests=0` | 422 on `guests` | Boundary | HotelSearchValidationTest | DONE |
| V7a | `guests=abc` | 422 on `guests` | Equivalence | HotelSearchValidationTest | DONE |
| V7b | `guests=-1` | 422 on `guests` | Equivalence | HotelSearchValidationTest | DONE |
| V7c | `guests=1.5` | 422 on `guests` | Equivalence | HotelSearchValidationTest | DONE |
| V8 | `guests=1` | no error on `guests` | Boundary | HotelSearchValidationTest | DONE |
| D1 | `check_in` is yesterday | 422 on `check_in`, none on `check_out` | Boundary | HotelSearchValidationTest | DONE |
| D2 | `check_in` is today | no error on `check_in` | Boundary | HotelSearchValidationTest | DONE |
| D3 | `check_out` equals `check_in` | 422 on `check_out` | Decision table | HotelSearchValidationTest | DONE |
| D4 | `check_out` before `check_in` | 422 on `check_out` | Decision table | HotelSearchValidationTest | DONE |
| D5a | `check_in=abc` | 422 on `check_in` | Equivalence | HotelSearchValidationTest | DONE |
| D5b | `check_out=2030-13-45` | 422 on `check_out` | Equivalence | HotelSearchValidationTest | DONE |
| D6 | Date formats other than `YYYY-MM-DD` | depends on decision | - | - | OPEN |
| D7 | Maximum stay length | depends on decision | - | - | OPEN |

Note: H1 is the baseline: every negative case assumes the other fields are valid, and H1 proves that assumption. V3, V5, V8 and D2 only prove "validation did not stop the request". Whether the search then returns the right data is covered by L2 cases below.

## 2. Search behaviour cases (L2, auto, needs test database)

| ID | Case | Expected | Test | Status |
|---|---|---|---|---|
| S1 | Happy path: known city, 1 guest, valid future dates | 200, matching hotels, `total_price` = sum of nights | HotelSearchTest (skipped) | TODO |
| S2 | City not in the database | 200 with empty list (not 404, not 422) | - | TODO |
| S3 | SQL injection string in `city` | 200 empty list, no SQL error | - | TODO |
| S4 | Room type with no inventory row for one night | Not offered | - | TODO |
| S5 | Capacity smaller than `guests` | Not offered | - | TODO |

## 3. Contract cases (L3, Postman)

| ID | Case | Expected | Method | Status |
|---|---|---|---|---|
| C1 | Happy path on a real environment | 200, response shape matches contract | auto after first manual run | TODO |
| C2 | One invalid request | 422 with `errors` object | auto | TODO |
| C3 | Request without `Accept: application/json` | documented behaviour (redirect or JSON) | manual first | TODO |

## 4. Decisions made

- `city`: maximum 100 characters; no restriction on characters (real names contain diacritics and apostrophes); surrounding spaces trimmed (Laravel trims request strings by default; hypothesis, to be proven by a test).
- `guests`: integer, minimum 1.
- `check_in`: today or later. `check_out`: strictly after `check_in`.

## 5. Open questions

- D6: accept only `YYYY-MM-DD` or any format Laravel's `date` rule allows?
- D7: maximum stay length.
- L2 needs a test database: `RefreshDatabase` on in-memory SQLite fails because the hotels migration adds its CHECK with MySQL-only `ALTER TABLE ... ADD CONSTRAINT`. S1 is written and skipped until tests run on a MySQL test database (or the migration is made SQLite-compatible).
