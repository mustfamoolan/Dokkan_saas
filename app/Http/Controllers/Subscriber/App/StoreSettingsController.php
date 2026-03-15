<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreConfig;
use App\Models\Warehouse;
use App\Models\Cashbox;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreSettingsController extends Controller
{
    public function index()
    {
        $store = Auth::guard('subscriber')->user()->store;
        $config = $store->config ?: $this->initializeConfig($store);
        
        $warehouses = Warehouse::where('is_active', true)->get();
        $cashboxes = Cashbox::all();
        $customers = Customer::where('is_active', true)->get();

        return view('subscriber.app.settings.store', compact('store', 'config', 'warehouses', 'cashboxes', 'customers'));
    }

    public function updateBranding(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:1024', // 1MB Max
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
        ]);

        $data = $request->only(['name', 'phone', 'address', 'currency', 'timezone']);

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = Storage::url($path);
        }

        $store->update($data);

        return redirect()->back()->with('success', 'تم تحديث بيانات الهوية بنجاح.');
    }

    public function updateOperational(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        $config = $store->config;

        $request->validate([
            'default_warehouse_id' => 'nullable|exists:warehouses,id,store_id,' . $store->id,
            'default_cashbox_id' => 'nullable|exists:cashboxes,id,store_id,' . $store->id,
            'default_walk_in_customer_id' => 'nullable|exists:customers,id,store_id,' . $store->id,
            'allow_sale_without_customer' => 'boolean',
            'allow_negative_stock' => 'boolean',
        ]);

        $config->update([
            'default_warehouse_id' => $request->default_warehouse_id,
            'default_cashbox_id' => $request->default_cashbox_id,
            'default_walk_in_customer_id' => $request->default_walk_in_customer_id,
            'allow_sale_without_customer' => $request->has('allow_sale_without_customer'),
            'allow_negative_stock' => $request->has('allow_negative_stock'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث الإعدادات التشغيلية بنجاح.');
    }

    public function updateNumbering(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        $config = $store->config;

        $request->validate([
            'sales_prefix' => 'required|string|max:10',
            'purchase_prefix' => 'required|string|max:10',
            'customer_payment_prefix' => 'required|string|max:10',
            'supplier_payment_prefix' => 'required|string|max:10',
        ]);

        $config->update($request->only([
            'sales_prefix', 
            'purchase_prefix', 
            'customer_payment_prefix', 
            'supplier_payment_prefix'
        ]));

        return redirect()->back()->with('success', 'تم تحديث إعدادات الترقيم بنجاح.');
    }

    public function updatePrinting(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        $config = $store->config;

        $request->validate([
            'print_header_title' => 'nullable|string|max:255',
            'print_footer_note' => 'nullable|string|max:1000',
        ]);

        $config->update([
            'print_header_title' => $request->print_header_title,
            'print_footer_note' => $request->print_footer_note,
            'show_logo_on_print' => $request->has('show_logo_on_print'),
            'show_phone_on_print' => $request->has('show_phone_on_print'),
            'show_address_on_print' => $request->has('show_address_on_print'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث إعدادات الطباعة بنجاح.');
    }

    private function initializeConfig($store)
    {
        return StoreConfig::create(['store_id' => $store->id]);
    }
}
