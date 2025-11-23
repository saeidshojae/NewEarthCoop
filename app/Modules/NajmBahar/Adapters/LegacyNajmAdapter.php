<?php

namespace App\Modules\NajmBahar\Adapters;

use App\Models\Spring;
use App\Modules\NajmBahar\Services\AccountNumberService;
use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\SubAccount;
use App\Modules\NajmBahar\Services\TransactionService;
use App\Modules\NajmBahar\Models\LedgerEntry;
use Illuminate\Support\Facades\Log;

class LegacyNajmAdapter
{
    /**
     * Mirror a legacy Spring model creation into NajmBahar module.
     * This runs after the legacy Spring record is created and should NOT
     * mutate legacy records. It attempts to create Najm accounts and
     * replicate initial funding and membership fee.
     */
    public static function onSpringCreated(Spring $spring)
    {
        try {
            $userId = $spring->user_id;

            // ensure system account exists
            $systemNumber = AccountNumberService::makeSystemAccountNumber();
            $system = Account::firstOrCreate([
                'account_number' => $systemNumber,
            ], [
                'name' => 'EarthCoop System Main Account',
                'type' => 'system',
                'balance' => 0,
            ]);

            // ensure membership subaccount exists
            $membershipCode = $systemNumber . '-001';
            $membership = SubAccount::firstOrCreate([
                'sub_account_code' => $membershipCode,
            ], [
                'account_id' => $system->id,
                'name' => 'Membership Fees',
                'balance' => 0,
            ]);

            // create user main account if not exists
            $userAccountNumber = AccountNumberService::makeMainAccountNumberForUser($userId);
            $userAcc = Account::firstOrCreate(['account_number' => $userAccountNumber], [
                'user_id' => $userId,
                'name' => 'NajmBahar Account',
                'type' => 'user',
                'balance' => 0,
            ]);

            $txService = new TransactionService();

            // Credit initial 10000 bex to user from system
            // Use idempotency key so repeated events don't double-credit
            try {
                // avoid duplicate funding by checking ledger entries for this user
                $userLedgerExists = LedgerEntry::where('account_id', $userAcc->id)
                    ->where('amount', 10000)
                    ->where('entry_type', 'credit')
                    ->exists();

                if (! $userLedgerExists) {
                    $idempInit = 'spring-init-' . $spring->id;
                    // also check transactions metadata for existing idempotency key (SQLite JSON)
                    $exists = \Illuminate\Support\Facades\DB::table('najm_transactions')
                        ->whereRaw("json_extract(metadata, '$.idempotency_key') = ?", [$idempInit])
                        ->exists();

                    if (! $exists) {
                        $txService->transfer($systemNumber, $userAccountNumber, 10000, 'Initial funding mirrored from legacy Spring', array_merge(['legacy_spring_id' => $spring->id], []), $idempInit);
                    }
                }
            } catch (\Throwable $e) {
                // log and continue
                Log::warning('NajmBahar adapter: initial funding failed: ' . $e->getMessage());
            }

            // Debit membership fee (12) from user to membership subaccount
            try {
                // ensure membership subaccount has an account record to receive credits
                // we'll treat subaccounts as separate accounts in NajmBahar by creating a synthetic Account record
                $membershipAccNumber = $membershipCode; // use sub account code as pseudo account_number
                $membershipAcc = Account::firstOrCreate(['account_number' => $membershipAccNumber], [
                    'name' => 'Membership SubAccount',
                    'type' => 'subaccount',
                    'balance' => 0,
                ]);

                // avoid duplicate membership fee by checking membership subaccount ledger
                $membershipLedgerExists = LedgerEntry::where('account_id', $membershipAcc->id)
                    ->where('amount', 12)
                    ->where('entry_type', 'credit')
                    ->exists();

                if (! $membershipLedgerExists) {
                    $idempFee = 'spring-membership-' . $spring->id;
                    $existsFee = \Illuminate\Support\Facades\DB::table('najm_transactions')
                        ->whereRaw("json_extract(metadata, '$.idempotency_key') = ?", [$idempFee])
                        ->exists();

                    if (! $existsFee) {
                        $txService->transfer($userAccountNumber, $membershipAccNumber, 12, 'Membership fee mirrored from legacy Spring', array_merge(['legacy_spring_id' => $spring->id], []), $idempFee);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('NajmBahar adapter: membership fee mirror failed: ' . $e->getMessage());
            }

        } catch (\Throwable $e) {
            Log::error('NajmBahar adapter failed: ' . $e->getMessage());
        }
    }
}
