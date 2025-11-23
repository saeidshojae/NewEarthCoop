# NajmBahar module (phase 1)

This module contains the initial scaffolding for the Najm Bahar banking system (phase 1: transactions).

Structure added:
- `Models/` — Eloquent models: `Account`, `SubAccount`, `Transaction`, `ScheduledTransaction`, `LedgerEntry`.
- `Services/` — helper services: `AccountNumberService`, `AccountService`.
- `database/migrations/2025_11_22_*` — migrations for module tables.
- `app/Console/Commands/NajmBaharProcessScheduled.php` — simple processor for scheduled transactions.

Notes:
- This is intentionally additive and does not change existing `Spring`/legacy Najm implementation.
- Next steps: implement `TransactionService` with atomic debit/credit and ledger entries, add API controllers and routes (`/api/najm-bahar/*`), write tests, and provide an adapter to route legacy calls to this module when ready.
