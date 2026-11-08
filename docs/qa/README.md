# QA documentation

| File | What it is | Changes |
|---|---|---|
| [`test-strategy.md`](test-strategy.md) | Process, test levels, design techniques, how AI is used | rarely |
| [`search/test-cases.md`](search/test-cases.md) | Case catalog for hotel search: ID, level, method, linked test, status, decisions, open questions | with every change to search |

Conventions:
- Every case has an ID. The automated test name starts with the same ID, for example `V2: search with empty city ...`.
- A case is `DONE` only when its test exists and passes.
- Each feature gets its own folder next to `search/` (booking, payment, ...).
