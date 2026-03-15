<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class PrintService
{
    /**
     * Get common store data for printing.
     */
    public function getStoreData()
    {
        $store = Auth::guard('subscriber')->user()->store;
        $config = $store->config;
        
        return [
            'name' => ($config && $config->print_header_title) ? $config->print_header_title : $store->name,
            'phone' => ($config && $config->show_phone_on_print) ? ($store->phone ?? '-') : null,
            'address' => ($config && $config->show_address_on_print) ? ($store->address ?? '-') : null,
            'logo' => ($config && $config->show_logo_on_print) ? ($store->logo ?? null) : null,
            'footer' => $config ? $config->print_footer_note : null,
        ];
    }

    /**
     * Prepare data for an invoice print.
     */
    public function prepareInvoiceData($invoice)
    {
        return [
            'store' => $this->getStoreData(),
            'document' => $invoice,
            'type' => ($invoice instanceof \App\Models\SalesInvoice) ? 'فاتورة بيع' : 'فاتورة شراء',
            'party' => ($invoice instanceof \App\Models\SalesInvoice) ? $invoice->customer : $invoice->supplier,
            'items' => $invoice->items()->with('product')->get(),
            'total_words' => '', // Placeholder for total in words if needed
        ];
    }

    /**
     * Prepare data for a payment print.
     */
    public function preparePaymentData($payment)
    {
        return [
            'store' => $this->getStoreData(),
            'document' => $payment,
            'type' => ($payment instanceof \App\Models\CustomerPayment) ? 'سند قبض' : 'سند صرف',
            'party' => ($payment instanceof \App\Models\CustomerPayment) ? $payment->customer : $payment->supplier,
        ];
    }
}
