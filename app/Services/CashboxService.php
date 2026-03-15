<?php

namespace App\Services;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class CashboxService
{
    /**
     * Record a transaction and update cashbox balance.
     */
    public function recordTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $cashbox = Cashbox::findOrFail($data['cashbox_id']);

            // Create the transaction
            $transaction = CashboxTransaction::create([
                'store_id' => $cashbox->store_id,
                'cashbox_id' => $cashbox->id,
                'amount' => $data['amount'],
                'direction' => $data['direction'],
                'type' => $data['type'],
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update balance
            if ($data['direction'] === 'in') {
                $cashbox->increment('current_balance', $data['amount']);
            } else {
                $cashbox->decrement('current_balance', $data['amount']);
            }

            return $transaction;
        });
    }

    /**
     * Create an expense and its associated transaction.
     */
    public function createExpense(array $expenseData)
    {
        return DB::transaction(function () use ($expenseData) {
            $expense = \App\Models\Expense::create($expenseData);

            $this->recordTransaction([
                'cashbox_id' => $expense->cashbox_id,
                'amount' => $expense->amount,
                'direction' => 'out',
                'type' => 'expense',
                'reference_type' => \App\Models\Expense::class,
                'reference_id' => $expense->id,
                'notes' => $expense->notes,
            ]);

            return $expense;
        });
    }

    /**
     * Record a customer payment and its associated transaction.
     */
    public function recordCustomerPayment(array $paymentData)
    {
        return DB::transaction(function () use ($paymentData) {
            $paymentData['payment_number'] = $this->generatePaymentNumber($paymentData['store_id']);
            
            $payment = \App\Models\CustomerPayment::create($paymentData);

            $this->recordTransaction([
                'cashbox_id' => $payment->cashbox_id,
                'amount' => $payment->amount,
                'direction' => 'in',
                'type' => 'income',
                'reference_type' => \App\Models\CustomerPayment::class,
                'reference_id' => $payment->id,
                'notes' => 'قبض دفعة من عميل: ' . $payment->customer->name . ($payment->notes ? ' - ' . $payment->notes : ''),
            ]);

            return $payment;
        });
    }

    public function generatePaymentNumber($storeId)
    {
        $lastPayment = \App\Models\CustomerPayment::where('store_id', $storeId)
            ->latest('id')
            ->first();

        if (!$lastPayment) {
            return 'CPAY-00001';
        }

        $lastNumber = str_replace('CPAY-', '', $lastPayment->payment_number);
        $nextNumber = str_pad((int)$lastNumber + 1, 5, '0', STR_PAD_LEFT);

        return 'CPAY-' . $nextNumber;
    }

    /**
     * Record a supplier payment and its associated transaction.
     */
    public function recordSupplierPayment(array $paymentData)
    {
        return DB::transaction(function () use ($paymentData) {
            $cashbox = Cashbox::findOrFail($paymentData['cashbox_id']);

            // Validate balance
            if ($cashbox->current_balance < $paymentData['amount']) {
                throw new Exception('عذراً، رصيد الصندوق غير كافٍ لإتمام عملية الدفع.');
            }

            $paymentData['payment_number'] = $this->generateSupplierPaymentNumber($paymentData['store_id']);
            
            $payment = \App\Models\SupplierPayment::create($paymentData);

            $this->recordTransaction([
                'cashbox_id' => $payment->cashbox_id,
                'amount' => $payment->amount,
                'direction' => 'out',
                'type' => 'expense',
                'reference_type' => \App\Models\SupplierPayment::class,
                'reference_id' => $payment->id,
                'notes' => 'صرف دفعة للمورد: ' . $payment->supplier->name . ($payment->notes ? ' - ' . $payment->notes : ''),
            ]);

            return $payment;
        });
    }

    public function generateSupplierPaymentNumber($storeId)
    {
        $lastPayment = \App\Models\SupplierPayment::where('store_id', $storeId)
            ->latest('id')
            ->first();

        if (!$lastPayment) {
            return 'SPAY-00001';
        }

        $lastNumber = str_replace('SPAY-', '', $lastPayment->payment_number);
        $nextNumber = str_pad((int)$lastNumber + 1, 5, '0', STR_PAD_LEFT);

        return 'SPAY-' . $nextNumber;
    }

    /**
     * Perform a balance adjustment.
     */
    public function adjustBalance(Cashbox $cashbox, $newBalance, $notes = null)
    {
        return DB::transaction(function () use ($cashbox, $newBalance, $notes) {
            $diff = $newBalance - $cashbox->current_balance;

            if ($diff == 0) return true;

            $direction = $diff > 0 ? 'in' : 'out';
            $amount = abs($diff);

            $this->recordTransaction([
                'cashbox_id' => $cashbox->id,
                'amount' => $amount,
                'direction' => $direction,
                'type' => 'adjustment',
                'notes' => $notes ?? 'تسوية رصيد يدوية',
            ]);

            return true;
        });
    }
}
