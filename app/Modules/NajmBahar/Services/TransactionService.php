<?php

namespace App\Modules\NajmBahar\Services;

use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\LedgerEntry;
use App\Modules\NajmBahar\Models\Transaction as NajmTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Exception as QueryException;

class TransactionService
{
    /**
     * Perform an atomic immediate transfer between two Najm accounts.
     * - $fromAccountNumber or null for system
     * - $toAccountNumber or null for system
     * - $amount in integer (behar smallest unit)
     * Returns NajmTransaction on success.
     * Throws exceptions on validation/insufficient funds.
     */
    public function transfer(string|null $fromAccountNumber, string $toAccountNumber, int $amount, string $description = null, array $meta = [], string|null $idempotencyKey = null): NajmTransaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        return DB::transaction(function () use ($fromAccountNumber, $toAccountNumber, $amount, $description, $meta, $idempotencyKey) {
            // idempotency: if provided, return existing transaction
            if ($idempotencyKey) {
                $existing = NajmTransaction::where('metadata->idempotency_key', $idempotencyKey)->first();
                if ($existing) return $existing;
            }
            // lock both accounts (order by account_number to avoid deadlocks)
            $numbers = array_filter([$fromAccountNumber, $toAccountNumber]);
            sort($numbers, SORT_STRING);

            $accounts = [];
            foreach ($numbers as $num) {
                $accounts[$num] = Account::where('account_number', $num)->lockForUpdate()->first();
                if (!$accounts[$num]) {
                    throw new \RuntimeException("Account not found: {$num}");
                }
            }

            // if from account is specified, check funds
            if ($fromAccountNumber) {
                $fromAcc = $accounts[$fromAccountNumber];
                if (intval($fromAcc->balance) < $amount) {
                    throw new \RuntimeException('Insufficient funds');
                }
                // debit
                $fromAcc->balance = intval($fromAcc->balance) - $amount;
                $fromAcc->save();
            }

            // credit
            $toAcc = $accounts[$toAccountNumber];
            $toAcc->balance = intval($toAcc->balance) + $amount;
            $toAcc->save();

            // create transaction record
            // merge idempotency key into metadata
            if ($idempotencyKey) {
                $meta['idempotency_key'] = $idempotencyKey;
            }

            $tx = NajmTransaction::create([
                'from_account_id' => $fromAccountNumber ? $accounts[$fromAccountNumber]->id : null,
                'to_account_id' => $toAcc->id,
                'amount' => $amount,
                'type' => 'immediate',
                'status' => 'completed',
                'metadata' => $meta,
                'description' => $description,
            ]);

            // ledger entries (double-entry)
            if ($fromAccountNumber) {
                LedgerEntry::create([
                    'transaction_id' => $tx->id,
                    'account_id' => $accounts[$fromAccountNumber]->id,
                    'amount' => -$amount,
                    'entry_type' => 'debit',
                    'meta' => $meta,
                ]);
            }

            LedgerEntry::create([
                'transaction_id' => $tx->id,
                'account_id' => $toAcc->id,
                'amount' => $amount,
                'entry_type' => 'credit',
                'meta' => $meta,
            ]);

            return $tx;
        });
    }
}
