@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f6f8fb;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .page-title {
        font-weight: 800;
        color: #0c1523;
        font-size: 1.6rem;
    }
    .btn-save {
        background-color: #0b2239;
        color: #ffffff;
        font-weight: 500;
        border-radius: 6px;
        padding: 8px 20px;
        font-size: 14px;
        border: none;
    }
    .btn-save:hover {
        background-color: #143252;
        color: #ffffff;
    }
    .card-custom {
        background: #ffffff;
        border-radius: 10px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    
    
    .customer-card-box {
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        background-color: #f8fafc;
        padding: 15px;
        cursor: pointer;
        position: relative;
        min-height: 125px;
    }
    .customer-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        z-index: 1050;
        border: 1px solid #e2e8f0;
        margin-top: 5px;
        overflow: hidden;
    }
    .customer-dropdown-menu.show {
        display: block;
    }
    .customer-search-input {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 12px 8px 34px;
        font-size: 13px;
        width: 100%;
    }
    .customer-list-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .customer-list-item:hover {
        background-color: #f1f5f9;
    }
    .avatar-circle {
        width: 36px;
        height: 36px;
        background-color: #cbd5e1;
        color: #475569;
        font-weight: 700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .add-customer-btn {
        background-color: #eef2f7;
        color: #0b2239;
        font-weight: 600;
        font-size: 13px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        border-top: 1px solid #e2e8f0;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .add-customer-btn:hover {
        background-color: #e2e8f0;
        color: #0b2239;
    }

   
    .form-label-custom {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }
    .input-icon-group {
        position: relative;
    }
    .input-icon-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        z-index: 4;
    }
    .input-icon-group .form-control {
        padding-left: 34px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        height: 42px;
        font-size: 13px;
        color: #334155;
    }

   
    .table-items th {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
    }
    .price-prefix {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 13px;
        z-index: 4;
    }
    .price-input {
        padding-left: 36px !important;
    }
    .btn-add-item {
        color: #0b2239;
        font-weight: 600;
        font-size: 13px;
        border: none;
        background: transparent;
        padding: 10px 0;
    }
</style>

<form action="{{ route('estimates.store') }}" method="POST" id="estimateForm">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="page-title mb-0">New Estimate</h3>
        <button type="submit" class="btn btn-save d-flex align-items-center gap-2">
            <i class="bi bi-floppy"></i> Save Estimate
        </button>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('estimates.index') }}" class="text-decoration-none text-muted">Estimates</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">New Estimate</li>
        </ol>
    </nav>

    <div class="card card-custom p-4 mb-4">
        <div class="row g-4">
          
            <div class="col-md-5">
                <div class="position-relative">
                    <div class="customer-card-box" id="customerBox">
                        <div id="customerSelectedView" class="d-none align-items-center gap-3">
                            <div class="avatar-circle" id="selectedAvatar">A</div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" id="selectedName"></h6>
                            </div>
                        </div>
                        <div id="customerPlaceholder" class="text-center py-3">
                            <i class="bi bi-person fs-3 text-muted"></i>
                            <div class="fw-semibold text-muted fs-6">Select Customer <span class="text-danger">*</span></div>
                        </div>
                        <input type="hidden" name="customer_id" id="customerId" required>
                    </div>

                   
                    <div class="customer-dropdown-menu p-2" id="customerDropdown">
                        <div class="position-relative mb-2">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="customer-search-input" id="customerSearch" placeholder="Search customer...">
                        </div>
                        <div style="max-height: 200px; overflow-y: auto;" id="customerList">
                            @forelse($customers as $customer)
                                <div class="customer-list-item" 
                                     data-id="{{ $customer->id }}" 
                                     data-name="{{ $customer->name }}">
                                    <div class="avatar-circle">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="fw-bold text-dark customer-item-title" style="font-size: 14px;">
                                            {{ $customer->name }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted fs-6">No customers found</div>
                            @endforelse
                        </div>
                        
                        
                        <div class="add-customer-btn">
                            <i class="bi bi-person-plus-fill"></i> Add New Customer
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Estimate Date <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="bi bi-calendar3"></i>
                            <input type="date" name="estimate_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Due Date <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="bi bi-calendar3"></i>
                            <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Estimate Number <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="bi bi-hash"></i>
                            <input type="text" name="estimate_number" class="form-control bg-light" value="{{ $nextEstimateNumber ?? 'EST - 000002' }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Ref Number</label>
                        <div class="input-icon-group">
                            <i class="bi bi-hash"></i>
                            <input type="text" name="ref_number" class="form-control" placeholder="#">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card card-custom p-4 mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th width="30"></th>
                        <th>Items</th>
                        <th width="120" class="text-center">Quantity</th>
                        <th width="180">Price</th>
                        <th width="140" class="text-end">Amount</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody id="itemRows">
                    <tr>
                        <td class="text-muted"><i class="bi bi-grid-3x2-gap-fill" style="cursor: grab; color: #cbd5e1;"></i></td>
                        <td>
                            <select name="items[0][item_id]" class="form-select item-select mb-2 border-0 bg-light p-2" style="border-radius: 6px; font-size: 13px;">
                                <option value="" selected disabled>Type or click to select an item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->price ?? 0 }}" data-description="{{ $item->description ?? '' }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="items[0][description]" class="form-control form-control-sm border-0 bg-transparent text-muted p-0 item-desc" placeholder="Type Item Description (optional)" style="font-size: 12px;">
                        </td>
                        <td>
                            <input type="number" name="items[0][qty]" class="form-control text-center qty" value="1" min="1" style="border-radius: 6px; border: 1px solid #e2e8f0; height: 38px;">
                        </td>
                        <td>
                            <div class="position-relative">
                                <span class="price-prefix">Rs</span>
                                <input type="number" step="0.01" name="items[0][price]" class="form-control price price-input" value="0.00" style="border-radius: 6px; border: 1px solid #e2e8f0; height: 38px;">
                            </div>
                        </td>
                        <td class="text-end fw-semibold text-dark" style="font-size: 14px;">
                            Rs <span class="row-amount">0.00</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-link text-muted p-0 remove-row">
                                <i class="bi bi-trash fs-6"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center border-top pt-3 mt-2">
            <button type="button" class="btn-add-item" id="addRowBtn">
                <i class="bi bi-basket me-1"></i> Add an Item
            </button>
        </div>
    </div>

   
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card card-custom p-3 mb-3">
                <label class="form-label-custom">Notes</label>
                <textarea name="notes" class="form-control border-0 p-0 shadow-none text-muted" rows="3" placeholder="Enter notes..." style="resize: none; font-size: 13px;"></textarea>
            </div>
            <div class="card card-custom p-3">
                <label class="form-label-custom d-block">Template <span class="text-danger">*</span></label>
                <div>
                    <span class="badge bg-light text-dark p-2 border" style="font-size: 13px;">
                        Template 1 <i class="bi bi-pencil ms-1 text-muted"></i>
                    </span>
                    <input type="hidden" name="template" value="template_1">
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-muted" style="font-size: 12px; letter-spacing: 0.5px;">SUB TOTAL</span>
                    <span class="fw-bold text-dark fs-6">Rs <span id="subTotal">0.00</span></span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-muted" style="font-size: 12px; letter-spacing: 0.5px;">DISCOUNT</span>
                    <div class="d-flex gap-1 align-items-center" style="width: 140px;">
                        <input type="number" id="discountInput" name="discount_value" class="form-control text-center" value="0" style="border-radius: 6px; border: 1px solid #e2e8f0; height: 34px;">
                        <select class="form-select" id="discountType" name="discount_type" style="border-radius: 6px; border: 1px solid #e2e8f0; height: 34px; width: 65px;">
                            <option value="flat">Rs</option>
                            <option value="percent">%</option>
                        </select>
                    </div>
                </div>

                <div class="text-end mb-3">
                    <a href="javascript:void(0)" class="text-decoration-none fw-bold" style="font-size: 12px; color: #0b2239;">+ Add Tax</a>
                </div>

                <hr class="my-3" style="color: #e2e8f0;">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark" style="font-size: 12px;">TOTAL AMOUNT:</span>
                    <h5 class="fw-bold text-dark mb-0">Rs <span id="grandTotal">0.00</span></h5>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 1;
    const itemsData = @json($items ?? []);

    const customerBox = document.getElementById('customerBox');
    const customerDropdown = document.getElementById('customerDropdown');
    const customerSearch = document.getElementById('customerSearch');

    
    customerBox.addEventListener('click', function () {
        customerDropdown.classList.toggle('show');
        if(customerDropdown.classList.contains('show')) {
            customerSearch.focus();
        }
    });

    
    document.addEventListener('click', function (e) {
        if (!customerBox.contains(e.target) && !customerDropdown.contains(e.target)) {
            customerDropdown.classList.remove('show');
        }
    });

    
    customerSearch.addEventListener('keyup', function () {
        const query = this.value.toLowerCase().trim();
        document.querySelectorAll('.customer-list-item').forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            if (name.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    document.getElementById('customerList').addEventListener('click', function (e) {
        const item = e.target.closest('.customer-list-item');
        if (item) {
            const customerId = item.getAttribute('data-id');
            const customerName = item.getAttribute('data-name');

            document.getElementById('customerId').value = customerId;
            document.getElementById('selectedName').innerText = customerName;
            document.getElementById('selectedAvatar').innerText = customerName.charAt(0).toUpperCase();

            document.getElementById('customerPlaceholder').classList.add('d-none');
            document.getElementById('customerSelectedView').classList.remove('d-none');
            document.getElementById('customerSelectedView').classList.add('d-flex');

            customerDropdown.classList.remove('show');
        }
    });

    
    document.getElementById('itemRows').addEventListener('change', function (e) {
        if (e.target.classList.contains('item-select')) {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const price = selectedOpt.getAttribute('data-price') || 0;
            const description = selectedOpt.getAttribute('data-description') || '';

            const tr = e.target.closest('tr');
            tr.querySelector('.price').value = parseFloat(price).toFixed(2);
            tr.querySelector('.item-desc').value = description;

            calculateTotals();
        }
    });

    document.getElementById('addRowBtn').addEventListener('click', function () {
        const tbody = document.getElementById('itemRows');
        const tr = document.createElement('tr');

        let options = '<option value="" selected disabled>Type or click to select an item</option>';
        itemsData.forEach(item => {
            options += `<option value="${item.id}" data-price="${item.price || 0}" data-description="${item.description || ''}">${item.name}</option>`;
        });

        tr.innerHTML = `
            <td class="text-muted"><i class="bi bi-grid-3x2-gap-fill" style="cursor: grab; color: #cbd5e1;"></i></td>
            <td>
                <select name="items[${rowIndex}][item_id]" class="form-select item-select mb-2 border-0 bg-light p-2" style="border-radius: 6px; font-size: 13px;">
                    ${options}
                </select>
                <input type="text" name="items[${rowIndex}][description]" class="form-control form-control-sm border-0 bg-transparent text-muted p-0 item-desc" placeholder="Type Item Description (optional)" style="font-size: 12px;">
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][qty]" class="form-control text-center qty" value="1" min="1" style="border-radius: 6px; border: 1px solid #e2e8f0; height: 38px;">
            </td>
            <td>
                <div class="position-relative">
                    <span class="price-prefix">Rs</span>
                    <input type="number" step="0.01" name="items[${rowIndex}][price]" class="form-control price price-input" value="0.00" style="border-radius: 6px; border: 1px solid #e2e8f0; height: 38px;">
                </div>
            </td>
            <td class="text-end fw-semibold text-dark" style="font-size: 14px;">
                Rs <span class="row-amount">0.00</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-link text-muted p-0 remove-row">
                    <i class="bi bi-trash fs-6"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
    });

    
    document.getElementById('itemRows').addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            if (document.querySelectorAll('#itemRows tr').length > 1) {
                e.target.closest('tr').remove();
                calculateTotals();
            }
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('price') || e.target.id === 'discountInput') {
            calculateTotals();
        }
    });

    document.getElementById('discountType').addEventListener('change', calculateTotals);

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#itemRows tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const amount = qty * price;
            row.querySelector('.row-amount').innerText = amount.toFixed(2);
            subtotal += amount;
        });

        document.getElementById('subTotal').innerText = subtotal.toFixed(2);
        const discVal = parseFloat(document.getElementById('discountInput').value) || 0;
        const discType = document.getElementById('discountType').value;
        const discAmt = (discType === 'percent') ? (subtotal * discVal) / 100 : discVal;

        let grandTotal = subtotal - discAmt;
        document.getElementById('grandTotal').innerText = Math.max(0, grandTotal).toFixed(2);
    }
});
</script>
@endsection