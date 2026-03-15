@extends('subscriber.layouts.app')

@section('title', 'نقطة البيع (POS)')

@section('content')
<div class="row g-3" id="posApp">
    <div class="col-md-7">
        <div class="card h-100 mb-0">
            <div class="card-header bg-white pb-0 border-0">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">المستودع</label>
                        <select class="form-select form-select-sm" id="warehouseSelect" onchange="pos.setWarehouse(this.value)">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ $defaultWarehouse->id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">العميل</label>
                        <select class="form-select form-select-sm" id="customerSelect" onchange="pos.setCustomer(this.value)">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3 position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><iconify-icon icon="solar:magnifer-linear"></iconify-icon></span>
                        <input type="text" id="productSearch" class="form-control border-start-0" placeholder="ابحث بالاسم، SKU، أو الباركود..." autocomplete="off">
                    </div>
                    <div id="searchResults" class="list-group position-absolute w-100 shadow-lg z-3 mt-1 d-none" style="max-height: 300px; overflow-y: auto;">
                        <!-- Results injected here -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>المنتج</th>
                                <th class="text-center" style="width: 120px;">الكمية</th>
                                <th class="text-end" style="width: 100px;">السعر</th>
                                <th class="text-end" style="width: 100px;">الإجمالي</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                            <!-- Cart items injected here -->
                        </tbody>
                    </table>
                </div>
                <div id="emptyCart" class="text-center py-5">
                    <iconify-icon icon="solar:cart-large-linear" class="display-1 text-light"></iconify-icon>
                    <p class="text-muted mt-2">السلة فارغة</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card h-100 mb-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 text-center">ملخص العملية</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">الإجمالي الفرعي:</span>
                    <span class="fw-bold h5 mb-0" id="subtotalDisplay">0.00</span>
                </div>
                <div class="mb-4">
                    <label class="form-label small text-muted mb-1">الخصم الإجمالي</label>
                    <input type="number" class="form-control" id="discountInput" value="0.00" oninput="pos.calculateTotals()">
                </div>
                
                <div class="mt-auto pt-4 border-top">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h4 mb-0">الصافي:</span>
                        <span class="h4 mb-0 text-primary fw-bold" id="totalDisplay">0.00</span>
                    </div>
                    
                    <button class="btn btn-primary btn-lg w-100 py-3 fw-bold" onclick="pos.submit()">
                        <iconify-icon icon="solar:check-circle-bold" class="me-1"></iconify-icon> إتمام عملية البيع
                    </button>
                    <button class="btn btn-soft-danger w-100 mt-2" onclick="pos.clearCart()">
                        مسح السلة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const pos = {
    cart: [],
    warehouseId: document.getElementById('warehouseSelect').value,
    customerId: document.getElementById('customerSelect').value,
    
    init() {
        this.setupSearch();
        this.render();
    },

    setWarehouse(id) {
        if (this.cart.length > 0 && !confirm('سيتم مسح السلة عند تغيير المستودع، هل أنت متأكد؟')) {
            document.getElementById('warehouseSelect').value = this.warehouseId;
            return;
        }
        this.warehouseId = id;
        this.clearCart();
    },

    setCustomer(id) {
        this.customerId = id;
    },

    setupSearch() {
        const input = document.getElementById('productSearch');
        const results = document.getElementById('searchResults');
        let timeout = null;

        input.addEventListener('input', () => {
            clearTimeout(timeout);
            const query = input.value.trim();
            if (query.length < 2) {
                results.classList.add('d-none');
                return;
            }

            timeout = setTimeout(async () => {
                const response = await fetch(`{{ route('subscriber.app.pos.search') }}?q=${query}&warehouse_id=${this.warehouseId}`);
                const products = await response.json();
                this.showResults(products);
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.classList.add('d-none');
            }
        });
    },

    showResults(products) {
        const results = document.getElementById('searchResults');
        results.innerHTML = '';
        
        if (products.length === 0) {
            results.innerHTML = '<div class="list-group-item text-muted small">لا توجد نتائج</div>';
        } else {
            products.forEach(p => {
                const item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                item.href = 'javascript:void(0)';
                item.innerHTML = `
                    <div>
                        <div class="fw-bold">${p.name}</div>
                        <small class="text-muted">SKU: ${p.sku} | متوفر: ${p.stock}</small>
                    </div>
                    <div class="text-primary fw-bold text-end">
                        ${p.sale_price.toLocaleString()} <br>
                        <small class="text-muted">د.ع</small>
                    </div>
                `;
                item.addEventListener('click', () => {
                    this.addToCart(p);
                    document.getElementById('productSearch').value = '';
                    results.classList.add('d-none');
                });
                results.appendChild(item);
            });
        }
        results.classList.remove('d-none');
    },

    addToCart(product) {
        const existing = this.cart.find(item => item.id === product.id);
        if (existing) {
            if (existing.quantity + 1 > product.stock) {
                alert('لا يمكن تجاوز الكمية المتوفرة في المستودع.');
                return;
            }
            existing.quantity += 1;
        } else {
            if (product.stock <= 0) {
                alert('المنتج غير متوفر في هذا المستودع.');
                return;
            }
            this.cart.push({
                id: product.id,
                name: product.name,
                price: product.sale_price,
                quantity: 1,
                max_stock: product.stock
            });
        }
        this.render();
    },

    updateQuantity(index, qty) {
        qty = parseFloat(qty);
        if (qty <= 0) return this.removeFromCart(index);
        
        const item = this.cart[index];
        if (qty > item.max_stock) {
            alert('لا يمكن تجاوز الكمية المتوفرة.');
            this.render();
            return;
        }
        
        item.quantity = qty;
        this.calculateTotals();
        document.getElementById(`line-total-${index}`).textContent = (item.quantity * item.price).toLocaleString();
    },

    removeFromCart(index) {
        this.cart.splice(index, 1);
        this.render();
    },

    clearCart() {
        this.cart = [];
        this.render();
    },

    calculateTotals() {
        let subtotal = 0;
        this.cart.forEach(item => {
            subtotal += item.quantity * item.price;
        });

        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal - discount;

        document.getElementById('subtotalDisplay').textContent = subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('totalDisplay').textContent = total.toLocaleString(undefined, {minimumFractionDigits: 2});
        
        return { subtotal, total, discount };
    },

    render() {
        const cartItems = document.getElementById('cartItems');
        const emptyCart = document.getElementById('emptyCart');
        cartItems.innerHTML = '';

        if (this.cart.length === 0) {
            emptyCart.classList.remove('d-none');
            this.calculateTotals();
            return;
        }

        emptyCart.classList.add('d-none');

        this.cart.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="fw-bold">${item.name}</div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary" onclick="pos.updateQuantity(${index}, ${item.quantity - 1})">-</button>
                        <input type="number" class="form-control text-center" value="${item.quantity}" onchange="pos.updateQuantity(${index}, this.value)">
                        <button class="btn btn-outline-secondary" onclick="pos.updateQuantity(${index}, ${item.quantity + 1})">+</button>
                    </div>
                </td>
                <td class="text-end font-monospace">${item.price.toLocaleString()}</td>
                <td class="text-end fw-bold font-monospace" id="line-total-${index}">${(item.quantity * item.price).toLocaleString()}</td>
                <td class="text-center">
                    <button class="btn btn-link text-danger p-0" onclick="pos.removeFromCart(${index})">
                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                    </button>
                </td>
            `;
            cartItems.appendChild(row);
        });

        this.calculateTotals();
    },

    async submit() {
        if (this.cart.length === 0) {
            alert('السلة فارغة.');
            return;
        }

        const totals = this.calculateTotals();
        
        const payload = {
            customer_id: this.customerId,
            warehouse_id: this.warehouseId,
            discount_amount: totals.discount,
            items: this.cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                unit_price: item.price
            })),
            _token: '{{ csrf_token() }}'
        };

        try {
            const response = await fetch('{{ route('subscriber.app.pos.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect_url;
            } else {
                alert('خطأ: ' + data.message);
            }
        } catch (e) {
            alert('حدث خطأ غير متوقع.');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => pos.init());
</script>

<style>
#searchResults .list-group-item:hover {
    background-color: #f8f9fa;
}
.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}
</style>
@endsection
