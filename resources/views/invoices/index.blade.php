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
    .btn-new-invoice {
        background-color: #0b2038;
        color: #ffffff;
        font-weight: 600;
        border-radius: 6px;
        padding: 8px 18px;
        font-size: 13px;
        border: none;
    }
    .btn-new-invoice:hover {
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

    
    .nav-tabs-custom {
        border-bottom: 1px solid #e2e8f0;
        gap: 24px;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 0 12px 0;
        background: transparent;
        border-bottom: 2px solid transparent;
        border-radius: 0;
    }
    .nav-tabs-custom .nav-link.active {
        color: #0c1523;
        font-weight: 700;
        border-bottom-color: #0c1523;
        background: transparent;
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

    
    .badge-status {
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
        letter-spacing: 0.3px;
    }
    .badge-draft { background-color: #fef3c7; color: #d97706; }
    .badge-completed { background-color: #d1fae5; color: #059669; }
    .badge-unpaid { background-color: #fef3c7; color: #d97706; }
    .badge-paid { background-color: #d1fae5; color: #059669; }

    
    .action-dropdown-menu {
        border: none;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        padding: 8px 0;
        min-width: 170px;
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
        <h3 class="page-title mb-0">Invoices</h3>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-filter-toggle d-flex align-items-center gap-2" id="toggleFilterBtn">
                Filter <i class="bi bi-x-lg" id="filterIcon"></i>
            </button>
            <a href="{{ route('invoices.create') }}" class="btn btn-new-invoice d-flex align-items-center gap-2 text-decoration-none">
                <i class="bi bi-plus-lg"></i> New Invoice
            </a>
        </div>
    </div>

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Invoices</li>
        </ol>
    </nav>

    
    <div class="filter-card mb-4" id="filterSection">
        <form action="{{ route('invoices.index') }}" method="GET" id="filterForm" onsubmit="return false;">
            <div class="d-flex justify-content-end mb-1">
                <span id="clearFiltersBtn" class="clear-all-link">Clear All</span>
            </div>
            <div class="row g-3">
                <div class="col-md">
                    <label class="filter-label">Customer</label>
                    <input type="text" id="customerSearchInput" class="form-control filter-control" placeholder="Type customer name..." value="{{ request('customer_name') }}">
                </div>
                <div class="col-md">
                    <label class="filter-label">Status</label>
                    <select name="status" class="form-select filter-control" id="statusFilterSelect">
                        <option value="DUE" {{ request('status', 'DUE') == 'DUE' ? 'selected' : '' }}>DUE</option>
                        <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                        <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>PAID</option>
                        <option value="OVERDUE" {{ request('status') == 'OVERDUE' ? 'selected' : '' }}>OVERDUE</option>
                        <option value="ALL" {{ request('status') == 'ALL' ? 'selected' : '' }}>ALL</option>
                    </select>
                </div>
                <div class="col-md">
                    <label class="filter-label">From</label>
                    <input type="date" name="from_date" class="form-control filter-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-0 d-none d-md-flex align-items-center justify-content-center pt-3 text-muted" style="width: 10px;">–</div>
                <div class="col-md">
                    <label class="filter-label">To</label>
                    <input type="date" name="to_date" class="form-control filter-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md">
                    <label class="filter-label">Invoice Number</label>
                    <input type="text" name="invoice_number" id="invoiceNumberInput" class="form-control filter-control" placeholder="#" value="{{ request('invoice_number') }}">
                </div>
            </div>
        </form>
    </div>

    
    <ul class="nav nav-tabs nav-tabs-custom mb-3" id="invoiceTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ request('tab', 'due') == 'due' ? 'active' : '' }}" href="{{ route('invoices.index', array_merge(request()->query(), ['tab' => 'due'])) }}">Due</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') == 'draft' ? 'active' : '' }}" href="{{ route('invoices.index', array_merge(request()->query(), ['tab' => 'draft'])) }}">Draft</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') == 'all' ? 'active' : '' }}" href="{{ route('invoices.index', array_merge(request()->query(), ['tab' => 'all'])) }}">All</a>
        </li>
    </ul>

    
    <div class="d-flex justify-content-end mb-2">
        <span class="text-dark" style="font-size: 12px;">
            Showing: <strong id="showingCount">{{ is_object($invoices) && method_exists($invoices, 'count') ? $invoices->count() : 1 }} of {{ is_object($invoices) && method_exists($invoices, 'total') ? $invoices->total() : 1 }}</strong>
        </span>
    </div>

    
    <div class="table-container-card mb-4">
        <div class="table-responsive">
            <table class="table custom-table align-middle mb-0" id="invoicesTable">
                <thead>
                    <tr>
                        <th width="40" class="ps-3"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                        <th>DATE</th>
                        <th>CUSTOMER</th>
                        <th>STATUS</th>
                        <th>PAID STATUS</th>
                        <th>NUMBER</th>
                        <th>AMOUNT DUE</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody id="invoicesTableBody">
                    @forelse($invoices as $invoice)
                    @php
                        $isPaid = strtolower($invoice->status) === 'paid' || strtolower($invoice->status) === 'completed';
                    @endphp
                    <tr class="invoice-row" data-customer="{{ strtolower($invoice->customer->name ?? '') }}" data-number="{{ strtolower($invoice->invoice_number ?? '') }}">
                        <td class="ps-3"><input type="checkbox" class="form-check-input row-checkbox" value="{{ $invoice->id }}"></td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-m-Y') }}</td>
                        <td class="customer-name-cell">{{ $invoice->customer->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge-status {{ $isPaid ? 'badge-completed' : 'badge-draft' }}">
                                {{ strtoupper($isPaid ? 'COMPLETED' : ($invoice->status ?? 'DRAFT')) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}">
                                {{ strtoupper($isPaid ? 'PAID' : 'UNPAID') }}
                            </span>
                        </td>
                        <td class="invoice-number-cell">{{ $invoice->invoice_number ?? 'N/A' }}</td>
                        <td>Rs {{ number_format($isPaid ? 0 : ($invoice->amount_due ?? $invoice->total_amount ?? 0), 2) }}</td>
                        <td class="text-end pe-3 position-relative">
                            
                            <div class="dropdown">
                                <button class="action-dots-btn dropdown-toggle-custom" type="button" id="dropdownMenuButton{{ $invoice->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $invoice->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->id) }}">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </li>
                                    @if(!$isPaid)
                                    <li>
                                        <form action="{{ route('invoices.markAsPaid', $invoice->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="bi bi-check-circle"></i> Mark as Paid
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-send"></i> Send Invoice
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-copy"></i> Clone Invoice
                                        </a>
                                    </li>
                                    <li>
                                        <form id="delete-form-{{ $invoice->id }}" action="{{ route('invoices.destroy', $invoice->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $invoice->id }})">
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
                        <td colspan="8" class="text-center py-4 text-muted">No invoices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this Invoice',
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
    const invoiceNumberInput = document.getElementById('invoiceNumberInput');
    const invoiceRows = document.querySelectorAll('.invoice-row');
    const showingCount = document.getElementById('showingCount');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    function filterTableRows() {
        const customerSearchTerm = customerSearchInput.value.toLowerCase().trim();
        const invoiceNumberSearchTerm = invoiceNumberInput.value.toLowerCase().trim();
        let visibleCount = 0;

        invoiceRows.forEach(row => {
            const customerName = row.getAttribute('data-customer') || '';
            const invoiceNum = row.getAttribute('data-number') || '';

            const matchesCustomer = customerName.includes(customerSearchTerm);
            const matchesInvoice = invoiceNum.includes(invoiceNumberSearchTerm);

            if (matchesCustomer && matchesInvoice) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (showingCount) {
            showingCount.innerText = `${visibleCount} of ${invoiceRows.length}`;
        }
    }

   
    if (customerSearchInput) {
        customerSearchInput.addEventListener('input', filterTableRows);
    }
    if (invoiceNumberInput) {
        invoiceNumberInput.addEventListener('input', filterTableRows);
    }

    
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function () {
            if (customerSearchInput) customerSearchInput.value = '';
            if (invoiceNumberInput) invoiceNumberInput.value = '';
            filterTableRows();
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
    if(selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>
@endsection