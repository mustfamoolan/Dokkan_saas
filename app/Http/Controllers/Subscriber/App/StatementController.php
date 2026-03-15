<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Supplier;
use App\Services\StatementService;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    protected $statementService;

    public function __construct(StatementService $statementService)
    {
        $this->statementService = $statementService;
    }

    /**
     * Customer Account Statement.
     */
    public function customerStatement(Customer $customer)
    {
        $transactions = $this->statementService->getCustomerStatement($customer);
        $balanceInfo = $this->statementService->getCustomerBalance($customer);
        
        return view('subscriber.app.statements.customer', compact('customer', 'transactions', 'balanceInfo'));
    }

    /**
     * Supplier Account Statement.
     */
    public function supplierStatement(Supplier $supplier)
    {
        $transactions = $this->statementService->getSupplierStatement($supplier);
        $balanceInfo = $this->statementService->getSupplierBalance($supplier);
        
        return view('subscriber.app.statements.supplier', compact('supplier', 'transactions', 'balanceInfo'));
    }

    /**
     * List of all Customer Balances.
     */
    public function customerBalances()
    {
        $customers = Customer::where('is_active', true)->get();
        $balances = $customers->map(function($customer) {
            return [
                'customer' => $customer,
                'balance' => $this->statementService->getCustomerBalance($customer)
            ];
        });

        return view('subscriber.app.statements.customer_balances', compact('balances'));
    }

    /**
     * List of all Supplier Balances.
     */
    public function supplierBalances()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $balances = $suppliers->map(function($supplier) {
            return [
                'supplier' => $supplier,
                'balance' => $this->statementService->getSupplierBalance($supplier)
            ];
        });

        return view('subscriber.app.statements.supplier_balances', compact('balances'));
    }
}
