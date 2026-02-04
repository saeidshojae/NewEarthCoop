<?php

namespace App\Modules\NajmBahar\Services;

use App\Modules\NajmBahar\Models\Fee;

class FeeService
{
    /**
     * محاسبه کارمزد برای یک تراکنش
     */
    public function calculateFee(int $amount, string $transactionType = 'immediate'): int
    {
        $fees = Fee::where('is_active', true)
            ->where(function($query) use ($transactionType) {
                $query->where('transaction_type', 'all')
                      ->orWhere('transaction_type', $transactionType);
            })
            ->get();

        $totalFee = 0;

        foreach ($fees as $fee) {
            if ($fee->appliesTo($transactionType)) {
                $totalFee += $fee->calculate($amount);
            }
        }

        return $totalFee;
    }

    /**
     * دریافت کارمزد فعال برای یک نوع تراکنش
     */
    public function getActiveFees(string $transactionType = 'all'): \Illuminate\Database\Eloquent\Collection
    {
        return Fee::where('is_active', true)
            ->where(function($query) use ($transactionType) {
                $query->where('transaction_type', 'all')
                      ->orWhere('transaction_type', $transactionType);
            })
            ->get();
    }

    /**
     * تست کارمزد قبل از اعمال
     */
    public function testFee(int $amount, string $transactionType = 'immediate'): array
    {
        $fees = $this->getActiveFees($transactionType);
        $details = [];
        $total = 0;

        foreach ($fees as $fee) {
            $feeAmount = $fee->calculate($amount);
            if ($feeAmount > 0) {
                $details[] = [
                    'fee_id' => $fee->id,
                    'fee_name' => $fee->name,
                    'type' => $fee->type,
                    'amount' => $feeAmount,
                ];
                $total += $feeAmount;
            }
        }

        return [
            'total_fee' => $total,
            'net_amount' => $amount - $total,
            'details' => $details,
        ];
    }
}

