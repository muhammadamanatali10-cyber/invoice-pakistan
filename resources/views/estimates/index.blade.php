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
    .btn-filter {
        border: 1px solid #0b2239;
        color: #0b2239;
        background: #fff;
        font-weight: 500;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 14px;
        cursor: pointer;
    }
    .btn-filter:hover {
        background: #f0f4f8;
        color: #0b2239;
    }
    .btn-primary-custom {
        background-color: #0b2239;
        color: #ffffff;
        font-weight: 500;
        border-radius: 6px;
        padding: 6px 18px;
        font-size: 14px;
        border: none;
    }
    .btn-primary-custom:hover {
        background-color: #143252;
        color: #ffffff;
    }
    .filter-card {
        background-color: #edf2f8;
        border-radius: 12px;
        border: none;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 6px;
        border: none;
        height: 38px;
        box-shadow: none;
        color: #64748b;
        font-size: 13px;
    }
    .filter-card label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    
   
    .nav-tabs {
        border-bottom: 1px solid #e2e8f0;
    }
    .nav-tabs .nav-link {
        border: none;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        margin-bottom: -1px;
    }
    .nav-tabs .nav-link.active {
        color: #0b2239;
        font-weight: 700;
        background: transparent;
        border-bottom: 2px solid #0b2239;
    }

    .table-container-card {
        background: #ffffff;
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        overflow: visible !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
    .custom-table th {
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f5f9;
        padding-top: 16px;
        padding-bottom: 16px;
    }
    .custom-table td {
        color: #334155;
        font-size: 13px;
        padding-top: 18px;
        padding-bottom: 18px;
        border-bottom: none;
    }
    .custom-table tr:hover {
        background-color: #f8fafc;
    }
    .form-check-input {
        border-color: #cbd5e1;
        width: 16px;
        height: 16px;
    }
    .clear-btn {
        color: #475569;
        font-size: 12px;
        font-weight: 500;
    }
    .action-dots {
        color: #94a3b8;
        cursor: pointer;
        background: transparent;
        border: none;
        outline: none;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .action-dots:hover {
        color: #0c1523;
        background-color: #e2e8f0;
    }
    
    
    .custom-dropdown-menu {
        display: none;
        position: absolute;
        right: 15px;
        top: 100%;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        padding: 6px 0;
        min-width: 180px;
        z-index: 9999 !important;
        list-style: none;
        margin: 0;
    }
    .custom-dropdown-menu.active {
        display: block !important;
    }
    .custom-dropdown-item {
        display: flex;
        align-items: center;
        padding: 8px 16px;
        font-size: 13px;
        color: #334155;
        text-decoration: none;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    .custom-dropdown-item:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
    .custom-dropdown-item.text-danger:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }
</style>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Estimates</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Estimates</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-filter d-flex align-items-center gap-2" id="toggleFilter">
            Filter <i class="bi bi-x-lg fw-bold" id="filterIcon" style="font-size: 12px;"></i>
        </button>
        <a href="{{ route('estimates.create') }}" class="btn btn-primary-custom d-flex align-items-center gap-2 text-decoration-none">
            <i class="bi bi-plus-lg"></i> New Estimate
        </a>
    </div>
</div>


<div class="filter-card p-4 mb-4" id="filterBox">
    <form method="GET" action="{{ route('estimates.index') }}" id="filterForm">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label mb-2">Customer</label>
                <select name="customer_id" class="form-select filter-input">
                    <option value="" selected>Type or click to select</option>
                    @if(isset($customers))
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label mb-2">Status</label>
                <select name="status" class="form-select filter-input">
                    <option value="" selected>Select a status</option>
                    <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                    <option value="SENT" {{ request('status') == 'SENT' ? 'selected' : '' }}>SENT</option>
                    <option value="ACCEPTED" {{ request('status') == 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-2">From</label>
                <input type="date" name="from_date" class="form-control filter-input" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label mb-2">To</label>
                <input type="date" name="to_date" class="form-control filter-input" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label mb-2">Estimate Number</label>
                <input type="text" name="estimate_number" class="form-control filter-input" value="{{ request('estimate_number') }}" placeholder="#">
            </div>
        </div>
        <div class="text-end mt-2">
            <a href="{{ route('estimates.index') }}" class="text-decoration-none clear-btn">Clear All</a>
        </div>
    </form>
</div>


<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request('tab') == 'draft' ? 'active' : '' }}" href="{{ route('estimates.index', array_merge(request()->query(), ['tab' => 'draft'])) }}">Draft</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('tab') == 'sent' ? 'active' : '' }}" href="{{ route('estimates.index', array_merge(request()->query(), ['tab' => 'sent'])) }}">Sent</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('tab', 'all') == 'all' ? 'active' : '' }}" href="{{ route('estimates.index', array_merge(request()->query(), ['tab' => 'all'])) }}">All</a>
    </li>
</ul>


<p class="text-end text-muted small mb-2 ms-1" style="font-size: 13px;">
    Showing: <b>{{ isset($estimates) ? $estimates->count() : 0 }} of {{ isset($estimates) ? $estimates->total() : 0 }}</b>
</p>


<div class="table-container-card p-2">
    <div class="table-responsive">
        <table class="table custom-table align-middle mb-0">
            <thead>
                <tr class="text-uppercase">
                    <th width="40" class="ps-3"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                    <th>DATE</th>
                    <th>CUSTOMER</th>
                    <th>STATUS</th>
                    <th>ESTIMATE</th>
                    <th>TOTAL</th>
                    <th width="60" class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($estimates ?? [] as $estimate)
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input item-checkbox"></td>
                    <td>{{ isset($estimate->date) ? \Carbon\Carbon::parse($estimate->date)->format('d-m-Y') : 'N/A' }}</td>
                    <td>{{ $estimate->customer->name ?? 'N/A' }}</td>
                    <td>
                        @php
                            $status = $estimate->status ?? 'DRAFT';
                            $badgeClass = match($status) {
                                'DRAFT' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                'SENT' => 'bg-info-subtle text-info border-info-subtle',
                                'ACCEPTED' => 'bg-success-subtle text-success border-success-subtle',
                                'REJECTED' => 'bg-danger-subtle text-danger border-danger-subtle',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} border px-2 py-1">
                            {{ $status }}
                        </span>
                    </td>
                    <td>{{ $estimate->estimate_number ?? 'N/A' }}</td>
                    <td><strong>Rs</strong> {{ number_format($estimate->total ?? 0, 2) }}</td>
                    <td class="text-center position-relative">
                        <button type="button" class="action-dots js-dropdown-toggle">
                            <i class="bi bi-three-dots fs-5"></i>
                        </button>
                        <ul class="custom-dropdown-menu">
                            <li>
                                <a class="custom-dropdown-item" href="{{ route('estimates.edit', $estimate->id) }}">
                                    <i class="bi bi-pencil me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <button type="button" class="custom-dropdown-item text-danger delete-btn" data-id="{{ $estimate->id }}">
                                    <i class="bi bi-trash me-2"></i> Delete
                                </button>
                            </li>
                            <li>
                                <a class="custom-dropdown-item" href="{{ route('estimates.show', $estimate->id) }}">
                                    <i class="bi bi-eye me-2"></i> View
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <!-- Direct Convert To Invoice Link -->
                            <li>
                                <a class="custom-dropdown-item text-primary fw-semibold" href="{{ route('invoices.create', ['from_estimate' => $estimate->id]) }}">
                                    <i class="bi bi-file-earmark-text me-2"></i> Convert to Invoice
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">There are no matching rows</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-3 border-0 shadow" style="border-radius: 12px;">
            <div class="modal-body">
                <div class="text-danger mb-2" style="font-size: 40px;">
                    <i class="bi bi-trash3"></i>
                </div>
                <h5 class="fw-bold text-dark mt-2">Are you sure?</h5>
                <p class="text-muted small">You will not be able to recover this estimate</p>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-light btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm px-3 fw-semibold">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleFilter');
    const filterBox = document.getElementById('filterBox');
    const filterIcon = document.getElementById('filterIcon');
    const filterForm = document.getElementById('filterForm');

    
    if (toggleBtn && filterBox) {
        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (filterBox.style.display === 'none') {
                filterBox.style.display = 'block';
                if (filterIcon) filterIcon.className = 'bi bi-x-lg fw-bold';
            } else {
                filterBox.style.display = 'none';
                if (filterIcon) filterIcon.className = 'bi bi-funnel-fill fw-bold';
            }
        });
    }

   
    let debounceTimeout = null;
    document.querySelectorAll('.filter-input').forEach(function(element) {
        element.addEventListener('change', function() {
            filterForm.submit();
        });
        
        if (element.tagName === 'INPUT' && element.type === 'text') {
            element.addEventListener('keyup', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(function() {
                    filterForm.submit();
                }, 500);
            });
        }
    });

    
    document.querySelectorAll('.js-dropdown-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const currentMenu = this.nextElementSibling;
            document.querySelectorAll('.custom-dropdown-menu').forEach(function (menu) {
                if (menu !== currentMenu) {
                    menu.classList.remove('active');
                }
            });
            currentMenu.classList.toggle('active');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.custom-dropdown-menu').forEach(function (menu) {
            menu.classList.remove('active');
        });
    });

    
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const itemId = this.getAttribute('data-id');
            const deleteForm = document.getElementById('deleteForm');
            if (deleteForm) {
                deleteForm.action = "{{ url('estimates') }}/" + itemId;
            }
            if (typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            }
        });
    });

    
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>
@endsection