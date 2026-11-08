# QA test strategy (project-wide)

How QA works in this project: process, techniques, levels, AI usage. Per-feature cases live next to the feature, for example `search/test-cases.md`.
These are working agreements for this project, not universal rules. When joining a team, ask what they call an "integration test".

## 1. How a QA change flows in a team

| Step | What happens | Output | Search example |
|---|---|---|---|
| 1. Requirements | PO and developer describe the behaviour and acceptance criteria (AC) | AC list | "Search needs city, dates, guests; invalid input returns 422 with field errors" |
| 2. Test design | QA turns AC into test cases with explicit techniques, **before** any automation | Test case catalog (`search/test-cases.md`) | Equivalence classes and boundaries per field |
| 3. Review | QA, developer and PO review the catalog; open questions get an owner | Decisions | Max city length, allowed characters, max stay |
| 4. Level + method | For each case choose the cheapest level that catches the bug, and automated vs manual | Level and method columns | `guests=0` -> API feature test, automated |
| 5. Implementation | Tests are written, run locally, reviewed in the PR | Tests in the repo | Pest tests in `backend/tests/Feature/` |
| 6. CI and regression | Tests run on every PR; red blocks the merge | Regression suite | Planned for phase 6 (GitHub Actions) |
| 7. Maintenance | A new bug gets a failing test first, then a fix, and the test stays | Growing regression set | Same flow for every bug |

## 2. Test design techniques used here

| Technique | Use it when | Search example |
|---|---|---|
| Equivalence partitioning | A field accepts a class of values that behave the same | `guests`: valid integers >= 1, 0, negatives, non-numeric, decimals |
| Boundary value analysis | A rule has an edge | `guests` 0 / 1; `city` 100 / 101 characters; `check_in` yesterday / today / tomorrow |
| Decision table | Several conditions interact | Only the pair `check_in` + `check_out` (`check_out` must be after `check_in`) |
| Error guessing / exploratory | Experience says where it breaks | `city='; DROP TABLE hotels`, non-Latin names, empty string |

Do not build one giant table for all fields. Independent fields use equivalence classes and boundaries; use a decision table only where one field's rule depends on another.

## 3. Levels and methods for this endpoint

Rule: test at the lowest level that still catches the bug.

| Level | What we check here | Tooling | Automated? |
|---|---|---|---|
| Feature / API test inside the app | All validation rules (422, field names, boundaries). No data needed | Pest | Yes, every PR |
| Feature test with a database | Correct results: happy path, unknown city returns `200 []`, availability over several nights | Pest + `RefreshDatabase` + factories (not done yet) | Yes |
| API test from outside | Contract of the response, headers (`Accept: application/json`), one happy path and one 422 on a real environment | Postman now, Playwright API + Zod in phase 6 | Yes (Postman first run is manual) |
| Exploratory | New behaviour, odd inputs, usability of error messages | Manual, DevTools | No, then convert findings into automated cases |

Automate: stable, repeated, high-risk, regression-prone checks. Keep manual: first exploration of new behaviour and anything that needs human judgement.

## 5. Working with AI in this workflow

Common practice today, not a standard: AI drafts, a human decides and verifies.

| Step | AI does | Human does |
|---|---|---|
| Requirements | Lists missing or contradictory points as questions | Asks the PO, decides |
| Test design | Drafts cases and edge cases from the AC | Removes noise, adds domain cases, owns the expected results |
| Test code | Writes repetitive tests from an existing template | Writes the first test of every new kind, predicts each result before the run, explains each test |
| Verification | Runs tests, summarizes failures | Confirms a test can fail |
| Review | Finds duplicates and gaps | Final approval |

Risks and the checks that answer them:

| Risk | Check used in this project |
|---|---|
| A generated test can never fail | Mutation check by hand: remove the rule, see the test go red, restore (done for `guests`) |
| Wrong expected value invented by AI | Expected values come from the AC and decisions in `search/test-cases.md`, not from AI |
| Tests that only assert "something is wrong" | Assert the exact status and field, and also assert that other fields have no error |
| Copying code nobody understands | Every test must be explained in the author's own words before merge |
| Hallucinated facts about code | Read the code, run it, label unverified claims as hypotheses |

