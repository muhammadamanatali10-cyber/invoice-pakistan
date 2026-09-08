@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .page-title {
        font-weight: 800;
        color: #0c1523;
        font-size: 1.5rem;
    }
    .btn-add-payment {
        background-color: #0b2038;
        color: #ffffff;
        font-weight: 600;
        border-radius: 6px;
        padding: 8px 18px;
        font-size: 13px;
        border: none;
    }
    .btn-add-payment:hover {
        background-color: #143252;
        color: #ffffff;
    }
    .btn-filter-toggle {
        background-color: #ffffff;
        color: #0c1523;
        font-weight: 600;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13px;
        border: 1px solid #d1d5db;
    }

    /* Filter Bar Styling */
    .filter-card {
        background: #eef2f7;
        border-radius: 8px;
        padding: 16px 20px 20px 20px;
        border: none;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 6px;
    }
    .filter-control {
        border-radius: 6px;
        border: 1px solid #ffffff;
        background-color: #ffffff;
        height: 38px;
        font-size: 13px;
        color: #334155;
    }
    .filter-control:focus {
        border-color: #cbd5e1;
        box-shadow: none;
    }
    .clear-all-link {
        font-size: 12px;
        color: #475569;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
    }
    .clear-all-link:hover {
        text-decoration: underline;
    }

   
    .table-container-card {
        background: #ffffff;
        border-radius: 8px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: visible !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
    .custom-table th {
        color: #0c1523;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 16px;
        text-transform: uppercase;
        background-color: #ffffff;
    }
    .custom-table td {
        padding: 18px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: none;
    }
    .custom-table tr:hover {
        background-color: #f8fafc;
    }

    
    .action-dropdown-menu {
        border: none;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        padding: 8px 0;
        min-width: 150px;
        z-index: 1050 !important;
    }
    .action-dropdown-menu .dropdown-item {
        font-size: 13px;
        color: #334155;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 400;
        background: transparent;
        border: none;
        width: 100%;
        text-align: left;
    }
    .action-dropdown-menu .dropdown-item:hover {
        background-color: #f8fafc;
        color: #0c1523;
    }
    .action-dropdown-menu .dropdown-item i {
        font-size: 14px;
        color: #475569;
    }
    .action-dots-btn {
        color: #94a3b8;
        cursor: pointer;
        font-size: 18px;
        border: none;
        background: transparent;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .action-dots-btn:hover {
        color: #334155;
        background-color: #f1f5f9;
    }

    
    .swal2-popup.custom-delete-modal {
        border-radius: 12px !important;
        padding: 30px 24px !important;
        width: 420px !important;
    }
    .custom-delete-modal .swal2-title {
        color: #2d3748 !important;
        font-weight: 700 !important;
        font-size: 22px !important;
        margin-top: 10px !important;
    }
    .custom-delete-modal .swal2-html-container {
        color: #718096 !important;
        font-size: 14px !important;
        margin-top: 8px !important;
    }
    .custom-delete-modal .swal2-actions {
        margin-top: 25px !important;
        gap: 10px !important;
    }
    .custom-delete-modal .swal2-confirm {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border-radius: 6px !important;
        padding: 8px 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        box-shadow: none !important;
    }
    .custom-delete-modal .swal2-cancel {
        background-color: #e5e7eb !important;
        color: #374151 !important;
        border-radius: 6px !important;
        padding: 8px 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        box-shadow: none !important;
    }
</style>

<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="page-title mb-0">Payments</h3>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-filter-toggle d-flex align-items-center gap-2" id="toggleFilterBtn">
                Filter <i class="bi bi-x-lg" id="filterIcon"></i>
            </button>
            <a href="{{ route('payments.create') }}" class="btn btn-add-payment d-flex align-items-center gap-2 text-decoration-none">
                <i class="bi bi-plus-lg"></i> Add Payment
            </a>
        </div>
    </div>

   
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Payments</li>
        </ol>
    </nav>

    
    <div class="filter-card mb-4" id="filterSection">
        <form action="{{ route('payments.index') }}" method="GET" id="filterForm" onsubmit="return false;">
            <div class="d-flex justify-content-end mb-1">
                <span id="clearFiltersBtn" class="clear-all-link">Clear All</span>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label">Customer</label>
                    <input type="text" id="customerSearchInput" class="form-control filter-control" placeholder="Type or click to select" value="{{ request('customer_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Payment Number</label>
                    <input type="text" id="paymentNumberInput" class="form-control filter-control" placeholder="#" value="{{ request('payment_number') }}">
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Payment Mode</label>
                    <select name="payment_mode" id="paymentModeSelect" class="form-select filter-control">
                        <option value="">Payment Mode</option>
                        <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank Transfer" {{ request('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cheque" {{ request('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    
    <div class="d-flex justify-content-start mb-2">
        <span class="text-dark" style="font-size: 12px;">
            Showing: <strong id="showingCount">{{ is_object($payments) && method_exists($payments, 'count') ? $payments->count() : 0 }} of {{ is_object($payments) && method_exists($payments, 'total') ? $payments->total() : 0 }}</strong>
        </span>
    </div>

   
    <div class="table-container-card mb-4">
        <div class="table-responsive">
            <table class="table custom-table align-middle mb-0" id="paymentsTable">
                <thead>
                    <tr>
                        <th width="40" class="ps-3"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                        <th>DATE</th>
                        <th>CUSTOMER</th>
                        <th>PAYMENT MODE</th>
                        <th>PAYMENT NUMBER</th>
                        <th>INVOICE</th>
                        <th>AMOUNT</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody id="paymentsTableBody">
                    @forelse($payments as $payment)
                    <tr class="payment-row" 
                        data-customer="{{ strtolower($payment->customer->name ?? '') }}" 
                        data-number="{{ strtolower($payment->payment_number ?? '') }}"
                        data-mode="{{ strtolower($payment->payment_mode ?? '') }}">
                        <td class="ps-3"><input type="checkbox" class="form-check-input row-checkbox" value="{{ $payment->id }}"></td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? now())->format('d-m-Y') }}</td>
                        <td>{{ $payment->customer->name ?? 'N/A' }}</td>
                        <td>{{ $payment->payment_mode ?? '-' }}</td>
                        <td>{{ $payment->payment_number }}</td>
                        <td>{{ $payment->invoice->invoice_number ?? '-' }}</td>
                        <td>Rs {{ number_format($payment->amount, 2) }}</td>
                        <td class="text-end pe-3 position-relative">
                            <!-- Action Dropdown Button -->
                            <div class="dropdown">
                                <button class="action-dots-btn dropdown-toggle-custom" type="button" id="dropdownMenuButton{{ $payment->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $payment->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('payments.edit', $payment->id) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('payments.show', $payment->id) }}">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <form id="delete-form-{{ $payment->id }}" action="{{ route('payments.destroy', $payment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $payment->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noDataRow">
                        <td colspan="8" class="text-center py-4 text-muted">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(is_object($payments) && method_exists($payments, 'links'))
        <div class="mt-3">
            {{ $payments->links() }}
        </div>
    @endif
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this Payment',
        iconHtml: '<svg width="60" height="60" viewBox="0 0 24 24" fill="#4f46e5"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        reverseButtons: false,
        customClass: {
            popup: 'custom-delete-modal',
            icon: 'border-0 mb-0'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const customerSearchInput = document.getElementById('customerSearchInput');
    const paymentNumberInput = document.getElementById('paymentNumberInput');
    const paymentModeSelect = document.getElementById('paymentModeSelect');
    const paymentRows = document.querySelectorAll('.payment-row');
    const showingCount = document.getElementById('showingCount');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    // Real-Time Client Side Table Filter
    function filterTableRows() {
        const customerSearchTerm = customerSearchInput.value.toLowerCase().trim();
        const paymentNumberSearchTerm = paymentNumberInput.value.toLowerCase().trim();
        const paymentModeValue = paymentModeSelect.value.toLowerCase().trim();
        let visibleCount = 0;

        paymentRows.forEach(row => {
            const customerName = row.getAttribute('data-customer') || '';
            const paymentNum = row.getAttribute('data-number') || '';
            const paymentMode = row.getAttribute('data-mode') || '';

            const matchesCustomer = customerName.includes(customerSearchTerm);
            const matchesPaymentNum = paymentNum.includes(paymentNumberSearchTerm);
            const matchesMode = paymentModeValue === '' || paymentMode === paymentModeValue;

            if (matchesCustomer && matchesPaymentNum && matchesMode) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (showingCount) {
            showingCount.innerText = `${visibleCount} of ${paymentRows.length}`;
        }
    }

    
    if (customerSearchInput) customerSearchInput.addEventListener('input', filterTableRows);
    if (paymentNumberInput) paymentNumberInput.addEventListener('input', filterTableRows);
    if (paymentModeSelect) paymentModeSelect.addEventListener('change', filterTableRows);

   
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function () {
            if (customerSearchInput) customerSearchInput.value = '';
            if (paymentNumberInput) paymentNumberInput.value = '';
            if (paymentModeSelect) paymentModeSelect.value = '';
            filterTableRows();
        });
    }

    
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    const filterSection = document.getElementById('filterSection');

    if (toggleFilterBtn && filterSection) {
        toggleFilterBtn.addEventListener('click', function () {
            if (filterSection.style.display === 'none') {
                filterSection.style.display = 'block';
            } else {
                filterSection.style.display = 'none';
            }
        });
    }

    
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }

    
    const dropdownElementList = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    dropdownElementList.forEach(function (dropdownToggleEl) {
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Dropdown(dropdownToggleEl);
        }
    });

    document.querySelectorAll('.dropdown-toggle-custom').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const currentMenu = this.nextElementSibling;
            
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                if (menu !== currentMenu) {
                    menu.classList.remove('show');
                }
            });

            if (currentMenu) {
                currentMenu.classList.toggle('show');
            }
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });
});
</script>
@endsection