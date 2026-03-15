<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\CustomerPayment;
use App\Models\SupplierPayment;
use App\Models\Customer;
use App\Models\Supplier;
use App\Services\PrintService;
use App\Services\StatementService;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    protected $printService;
    protected $statementService;

    public function __construct(PrintService $printService, StatementService $statementService)
    {
        $this->printService = $printService;
        $this->statementService = $statementService;
    }

    public function salesInvoice(SalesInvoice $invoice)
    {
        $data = $this->printService->prepareInvoiceData($invoice);
        return view('subscriber.app.print.invoice', $data);
    }

    public function purchaseInvoice(PurchaseInvoice $invoice)
    {
        $data = $this->printService->prepareInvoiceData($invoice);
        return view('subscriber.app.print.invoice', $data);
    }

    public function customerPayment(CustomerPayment $payment)
    {
        $data = $this->printService->preparePaymentData($payment);
        return view('subscriber.app.print.payment', $data);
    }

    public function supplierPayment(SupplierPayment $payment)
    {
        $data = $this->printService->preparePaymentData($payment);
        return view('subscriber.app.print.payment', $data);
    }

    public function customerStatement(Customer $customer)
    {
        $statement = $this->statementService->getCustomerStatement($customer);
        $store = $this->printService->getStoreData();
        return view('subscriber.app.print.statement', [
            'store' => $store,
            'party' => $customer,
            'type' => 'كشف حساب عميل',
            'statement' => $statement
        ]);
    }

    public function supplierStatement(Supplier $supplier)
    {
        $statement = $this->statementService->getSupplierStatement($supplier);
        $store = $this->printService->getStoreData();
        return view('subscriber.app.print.statement', [
            'store' => $store,
            'party' => $supplier,
            'type' => 'كشف حساب مورد',
            'statement' => $statement
        ]);
    }
}
