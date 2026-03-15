@extends('subscriber.layouts.app')

@section('title', 'تعديل فاتورة بيع')

@section('content')
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('subscriber.app.sales.update', $sale) }}" method="POST" id="salesForm">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تعديل الفاتورة: {{ $sale->invoice_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">العميل</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">المستودع</label>
                            <select name="warehouse_id" class="form-select" required>
                                <option value="">اختر المستودع</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $sale->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">التاريخ</label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $sale->invoice_date->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <hr>

                    <h5>المنتجات</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 40%;">المنتج</th>
                                    <th>الكمية</th>
                                    <th>سعر البيع</th>
                                    <th>الإجمالي</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $index => $item)
                                <tr class="item-row">
                                    <td>
                                        <select name="items[{{ $index }}][product_id]" class="form-select product-select" required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control qty-input" step="0.01" min="0.01" required value="{{ $item->quantity }}">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][unit_price]" class="form-control price-input" step="0.01" min="0" required value="{{ $item->unit_price }}">
                                    </td>
                                    <td class="line-total text-center align-middle fw-bold">{{ number_format($item->line_total, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-item"><iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-soft-primary btn-sm mt-2" id="addItem">
                        <iconify-icon icon="solar:add-circle-bold"></iconify-icon> إضافة سطر جديد
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $sale->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white text-center py-2 h5 mb-0">
                    ملخص الفاتورة
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>المبلغ الإجمالي:</span>
                        <span id="subtotalDisplay">{{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الخصم</label>
                        <input type="number" name="discount_amount" id="discountInput" class="form-control" step="0.01" min="0" value="{{ $sale->discount_amount }}">
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between h4 mb-4">
                        <span>الصافي:</span>
                        <span id="totalDisplay" class="text-primary fw-bold">{{ number_format($sale->total_amount, 2) }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">حالة الفاتورة</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ $sale->status == 'draft' ? 'selected' : '' }}>مسودة (حفظ فقط)</option>
                            <option value="posted">معتمد (إنقاص المخزون)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-2">تحديث الفاتورة</button>
                    <a href="{{ route('subscriber.app.sales.index') }}" class="btn btn-light w-100 italic">إلغاء</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
    const addButton = document.getElementById('addItem');
    let rowCount = {{ count($sale->items) }};

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const lineTotal = qty * price;
            row.querySelector('.line-total').textContent = lineTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
            subtotal += lineTotal;
        });

        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal - discount;

        document.getElementById('subtotalDisplay').textContent = subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('totalDisplay').textContent = total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    addButton.addEventListener('click', function() {
        // Clone the first row as a template
        const templateRow = document.querySelector('.item-row');
        const newRow = templateRow.cloneNode(true);
        
        newRow.querySelectorAll('input').forEach(input => {
            input.value = input.classList.contains('qty-input') ? 1 : 0;
            input.name = input.name.replace(/items\[\d+\]/, 'items[' + rowCount + ']');
        });
        const select = newRow.querySelector('select');
        select.name = select.name.replace(/items\[\d+\]/, 'items[' + rowCount + ']');
        select.value = '';
        
        newRow.querySelector('.line-total').textContent = '0.00';
        table.appendChild(newRow);
        rowCount++;
        attachRowEvents(newRow);
    });

    function attachRowEvents(row) {
        row.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateTotals();
            }
        });

        row.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('change', calculateTotals);
            element.addEventListener('input', calculateTotals);
        });

        row.querySelector('.product-select').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.dataset.price) {
                row.querySelector('.price-input').value = option.dataset.price;
                calculateTotals();
            }
        });
    }

    document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
    document.getElementById('discountInput').addEventListener('input', calculateTotals);
});
</script>
@endsection
