<?php

namespace App\Modules\NajmBahar\Services;

use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\Transaction;
use App\Modules\NajmBahar\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class AccountService
{
    /**
     * Create a main account for user without altering legacy Najm implementation.
     * Returns created Account model.
     */
    public function createMainAccountForUser(int $userId, string $name = 'NajmBahar Account'): Account
    {
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($userId);

        return Account::create([
            'account_number' => $accountNumber,
            'user_id' => $userId,
            'name' => $name,
            'type' => 'user',
            'balance' => 0,
        ]);
    }

    /**
     * Create a simple immediate transaction between accounts.
     * This is a skeleton; business rules (fees, checks) must be implemented.
     */
    public function createTransaction(array $payload): Transaction
    {
        // for now, use simple create; production should call TransactionService->transfer for atomic operations
        return Transaction::create($payload);
    }
}
