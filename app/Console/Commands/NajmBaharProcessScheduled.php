<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Modules\NajmBahar\Models\ScheduledTransaction;
use App\Modules\NajmBahar\Models\Transaction as NajmTransaction;
use App\Modules\NajmBahar\Services\TransactionService;
use Carbon\Carbon;

class NajmBaharProcessScheduled extends Command
{
    protected $signature = 'najm-bahar:process-scheduled';
    protected $description = 'Process scheduled NajmBahar transactions that are due';

    public function handle()
    {
        $now = Carbon::now();
        $items = ScheduledTransaction::where('status', 'scheduled')
            ->where('execute_at', '<=', $now)
            ->where('attempts', '<', 5)
            ->orderBy('execute_at', 'asc')
            ->limit(100)
            ->get();

        $processed = 0;
        $service = app()->make(TransactionService::class);

        foreach ($items as $item) {
            try {
                DB::transaction(function () use ($item, $service, &$processed) {
                    $payload = $item->payload ?? [];

                    // Expected payload keys: from_account_number, to_account_number, amount, description, metadata, idempotency_key
                    $from = $payload['from_account_number'] ?? null;
                    $to = $payload['to_account_number'] ?? ($payload['to_account'] ?? null);
                    $amount = isset($payload['amount']) ? intval($payload['amount']) : 0;
                    $description = $payload['description'] ?? null;
                    $meta = $payload['metadata'] ?? [];
                    $idempotency = $payload['idempotency_key'] ?? ($item->id ? "scheduled-{$item->id}" : null);

                    if (!$to || $amount <= 0) {
                        throw new \RuntimeException('Invalid scheduled payload');
                    }

                    // perform transfer (TransactionService will create ledger entries and transactions)
                    $tx = $service->transfer($from, $to, $amount, $description, $meta, $idempotency);

                    // mark scheduled item processed
                    $item->status = 'processed';
                    $item->attempts = ($item->attempts ?? 0) + 1;
                    $item->last_error = null;
                    $item->save();

                    $processed++;
                });
            } catch (\Throwable $e) {
                // record failure and increment attempts
                $item->attempts = ($item->attempts ?? 0) + 1;
                $item->last_error = substr($e->getMessage(), 0, 1000);
                $item->status = $item->attempts >= 5 ? 'failed' : 'scheduled';
                $item->save();
                // continue with next item
                continue;
            }
        }

        $this->info('NajmBahar scheduled processing completed. Processed: ' . $processed);
    }
}
