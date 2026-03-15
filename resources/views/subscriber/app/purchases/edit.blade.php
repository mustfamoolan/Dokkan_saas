@extends('subscriber.layouts.app')

@section('title', 'تعديل فاتورة شراء')

@section('content')
<form action="{{ route('subscriber.app.purchases.update', $purchase) }}" method="POST" id="purchaseForm">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تعديل الفاتورة: {{ $purchase->invoice_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">المورد</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">اختر المورد</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">المستودع</label>
                            <select name="warehouse_id" class="form-select" required>
                                <option value="">اختر المستودع</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $purchase->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">التاريخ</label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $purchase->invoice_date->format('Y-m-d')) }}" required>
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
                                    <th>سعر التكلفة</th>
                                    <th>الإجمالي</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $index => $item)
                                <tr class="item-row">
                                    <td>
                                        <select name="items[{{ $index }}][product_id]" class="form-select product-select" required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->cost_price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control qty-input" step="0.01" min="0.01" required value="{{ $item->quantity }}">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][unit_cost]" class="form-control cost-input" step="0.01" min="0" required value="{{ $item->unit_cost }}">
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
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $purchase->notes) }}</textarea>
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
                        <span id="subtotalDisplay">{{ number_format($purchase->subtotal, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الخصم</label>
                        <input type="number" name="discount_amount" id="discountInput" class="form-control" step="0.01" min="0" value="{{ $purchase->discount_amount }}">
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between h4 mb-4">
                        <span>الصافي:</span>
                        <span id="totalDisplay" class="text-primary fw-bold">{{ number_format($purchase->total_amount, 2) }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">حالة الفاتورة</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ $purchase->status == 'draft' ? 'selected' : '' }}>مسودة (حفظ فقط)</option>
                            <option value="posted">معتمد (تغذية المخزون)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-2">تحديث الفاتورة</button>
                    <a href="{{ route('subscriber.app.purchases.index') }}" class="btn btn-light w-100 italic">إلغاء</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
    const addButton = document.getElementById('addItem');
    let rowCount = {{ count($purchase->items) }};

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            const lineTotal = qty * cost;
            row.querySelector('.line-total').textContent = lineTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
            subtotal += lineTotal;
        });

        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal - discount;

        document.getElementById('subtotalDisplay').textContent = subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('totalDisplay').textContent = total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    addButton.addEventListener('click', function() {
        const newRow = table.rows[0].cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => {
            input.value = input.classList.contains('qty-input') ? 1 : 0;
            input.name = input.name.replace(/items\[\d+\]/, 'items[' + rowCount + ']');
        });
        newRow.querySelector('select').name = newRow.querySelector('select').name.replace(/items\[\d+\]/, 'items[' + rowCount + ']');
        newRow.querySelector('select').value = '';
        newRow.querySelector('.line-total').textContent = '0.00';
        table.appendChild(newRow);
        rowCount++;
        attachRowEvents(newRow);
    });

    function attachRowEvents(row) {
        row.querySelector('.remove-item').addEventListener('click', function() {
            if (table.rows.length > 1) {
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
                row.querySelector('.cost-input').value = option.dataset.price;
                calculateTotals();
            }
        });
    }

    document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
    document.getElementById('discountInput').addEventListener('input', calculateTotals);
});
</script>
@endsection
