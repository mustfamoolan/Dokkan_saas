<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\CustomerPayment;
use App\Models\SupplierPayment;
use Illuminate\Support\Collection;

class StatementService
{
    /**
     * Get Customer Account Statement.
     */
    public function getCustomerStatement(Customer $customer): Collection
    {
        $transactions = collect();

        // 1. Opening Balance
        $openingBalance = (float) $customer->opening_balance;
        $openingType = $customer->opening_balance_type; // 'debit' (عليه), 'credit' (له)

        $transactions->push([
            'date' => $customer->created_at,
            'type' => 'رصيد افتتاحي',
            'reference' => '-',
            'description' => 'الرصيد الافتتاحي عند التأسيس',
            'debit' => $openingType === 'debit' ? $openingBalance : 0,
            'credit' => $openingType === 'credit' ? $openingBalance : 0,
            'sort_date' => $customer->created_at->format('Y-m-d H:i:s'),
        ]);

        // 2. Sales Invoices (Posted only)
        $invoices = SalesInvoice::where('customer_id', $customer->id)
            ->where('status', 'posted')
            ->get();

        foreach ($invoices as $invoice) {
            $transactions->push([
                'date' => $invoice->invoice_date,
                'type' => 'فاتورة بيع',
                'reference' => $invoice->invoice_number,
                'description' => 'فاتورة مبيعات آجل',
                'debit' => (float) $invoice->total_amount,
                'credit' => 0,
                'sort_date' => $invoice->invoice_date->format('Y-m-d') . ' 00:00:00',
            ]);
        }

        // 3. Customer Payments
        $payments = CustomerPayment::where('customer_id', $customer->id)->get();

        foreach ($payments as $payment) {
            $transactions->push([
                'date' => $payment->payment_date,
                'type' => 'سند قبض',
                'reference' => $payment->payment_number,
                'description' => 'دفعة نقدية من عميل ' . ($payment->reference_number ? ' (مرجع: ' . $payment->reference_number . ')' : ''),
                'debit' => 0,
                'credit' => (float) $payment->amount,
                'sort_date' => $payment->payment_date->format('Y-m-d') . ' 00:00:01', // Ensure payment after invoice on same day for visual logic
            ]);
        }

        // Sort by date and calculate running balance
        $sortedTransactions = $transactions->sortBy('sort_date')->values();
        $runningBalance = 0;

        return $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += ($item['debit'] - $item['credit']);
            $item['balance'] = $runningBalance;
            return $item;
        });
    }

    /**
     * Get Current Customer Net Balance.
     */
    public function getCustomerBalance(Customer $customer): array
    {
        $statement = $this->getCustomerStatement($customer);
        $finalBalance = $statement->last()['balance'] ?? 0;

        return [
            'amount' => abs($finalBalance),
            'type' => $finalBalance >= 0 ? 'debit' : 'credit', // debit = عليه (owed to us), credit = له (we owe him)
            'label' => $finalBalance >= 0 ? 'عليه (مدين)' : 'له (دائن)',
            'color' => $finalBalance >= 0 ? 'danger' : 'success',
        ];
    }

    /**
     * Get Supplier Account Statement.
     */
    public function getSupplierStatement(Supplier $supplier): Collection
    {
        $transactions = collect();

        // 1. Opening Balance
        $openingBalance = (float) $supplier->opening_balance;
        $openingType = $supplier->opening_balance_type; // 'credit' (علينا له), 'debit' (لنا عنده)

        $transactions->push([
            'date' => $supplier->created_at,
            'type' => 'رصيد افتتاحي',
            'reference' => '-',
            'description' => 'الرصيد الافتتاحي عند التأسيس',
            'debit' => $openingType === 'debit' ? $openingBalance : 0,
            'credit' => $openingType === 'credit' ? $openingBalance : 0,
            'sort_date' => $supplier->created_at->format('Y-m-d H:i:s'),
        ]);

        // 2. Purchase Invoices (Posted only)
        $invoices = PurchaseInvoice::where('supplier_id', $supplier->id)
            ->where('status', 'posted')
            ->get();

        foreach ($invoices as $invoice) {
            $transactions->push([
                'date' => $invoice->invoice_date,
                'type' => 'فاتورة شراء',
                'reference' => $invoice->invoice_number,
                'description' => 'فاتورة مشتريات آجل',
                'debit' => 0,
                'credit' => (float) $invoice->total_amount,
                'sort_date' => $invoice->invoice_date->format('Y-m-d') . ' 00:00:00',
            ]);
        }

        // 3. Supplier Payments
        $payments = SupplierPayment::where('supplier_id', $supplier->id)->get();

        foreach ($payments as $payment) {
            $transactions->push([
                'date' => $payment->payment_date,
                'type' => 'سند صرف',
                'reference' => $payment->payment_number,
                'description' => 'دفعة نقدية لمورد ' . ($payment->reference_number ? ' (مرجع: ' . $payment->reference_number . ')' : ''),
                'debit' => (float) $payment->amount,
                'credit' => 0,
                'sort_date' => $payment->payment_date->format('Y-m-d') . ' 00:00:01',
            ]);
        }

        // Sort for Supplier: Credit increases balance (we owe), Debit decreases it
        $sortedTransactions = $transactions->sortBy('sort_date')->values();
        $runningBalance = 0;

        return $sortedTransactions->map(function ($item) use (&$runningBalance) {
            // Balance logic for supplier: Credit is positive (we owe them)
            $runningBalance += ($item['credit'] - $item['debit']);
            $item['balance'] = $runningBalance;
            return $item;
        });
    }

    /**
     * Get Current Supplier Net Balance.
     */
    public function getSupplierBalance(Supplier $supplier): array
    {
        $statement = $this->getSupplierStatement($supplier);
        $finalBalance = $statement->last()['balance'] ?? 0;

        return [
            'amount' => abs($finalBalance),
            'type' => $finalBalance >= 0 ? 'credit' : 'debit', // credit = له (we owe him), debit = علينا (he owes us/overpayment)
            'label' => $finalBalance >= 0 ? 'له (دائن)' : 'عليه (مدين)',
            'color' => $finalBalance >= 0 ? 'danger' : 'success',
        ];
    }
}
