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
    }
    .btn-filter:hover {
        background: #f0f4f8;
        color: #0b2239;
    }
    .btn-add-customer {
        background-color: #0b2239;
        color: #ffffff;
        font-weight: 500;
        border-radius: 6px;
        padding: 6px 18px;
        font-size: 14px;
        border: none;
    }
    .btn-add-customer:hover {
        background-color: #143252;
        color: #ffffff;
    }
    .filter-card {
        background-color: #edf2f8;
        border-radius: 12px;
        border: none;
    }
    .filter-card .form-control {
        border-radius: 6px;
        border: none;
        height: 38px;
        box-shadow: none;
    }
    .filter-card label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
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
        min-width: 120px;
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
        <h3 class="page-title mb-1">Customers</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    <a href="{{ route('customers.index') }}" class="text-decoration-none text-muted">Customers</a>
                </li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-filter d-flex align-items-center gap-2" id="toggleFilter">
            Filter <i class="bi bi-x-lg fw-bold" style="font-size: 12px;"></i>
        </button>
        <a href="{{ route('customers.create') }}" class="btn btn-add-customer d-flex align-items-center gap-2 text-decoration-none">
            <i class="bi bi-plus-lg"></i> New Customer
        </a>
    </div>
</div>

<div class="filter-card p-4 mb-4" id="filterBox">
    <form method="GET" action="{{ route('customers.index') }}" id="filterForm">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label mb-2">Display Name</label>
                <input type="text" name="display_name" class="form-control" value="{{ request('display_name') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label mb-2">Contact Name</label>
                <input type="text" name="contact_name" class="form-control" value="{{ request('contact_name') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label mb-2">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ request('phone') }}">
            </div>
        </div>
        <div class="text-end mt-2">
            <a href="{{ route('customers.index') }}" class="text-decoration-none clear-btn">Clear All</a>
        </div>
    </form>
</div>

<div class="text-muted mb-3 ms-1" style="font-size: 13px;">
    Showing: <span class="fw-bold text-dark">{{ method_exists($customers, 'firstItem') ? ($customers->firstItem() ?? 0) : count($customers) }}</span> of <span class="fw-bold text-dark">{{ method_exists($customers, 'total') ? $customers->total() : count($customers) }}</span>
</div>

<div class="table-container-card p-2">
    <div class="table-responsive">
        <table class="table custom-table align-middle mb-0">
            <thead>
                <tr class="text-uppercase">
                    <th width="50" class="ps-3"><input type="checkbox" class="form-check-input"></th>
                    <th>DISPLAY NAME</th>
                    <th>CONTACT NAME</th>
                    <th>PHONE</th>
                    <th>AMOUNT DUE</th>
                    <th>ADDED ON</th>
                    <th width="60" class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $cust)
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input"></td>
                    <td>{{ $cust->display_name }}</td>
                    <td>{{ $cust->primary_contact_name ?? '-' }}</td>
                    <td>{{ $cust->phone ?? '-' }}</td>
                    <td><b>Rs</b> {{ number_format($cust->amount_due ?? 0, 2) }}</td>
                    <td>{{ isset($cust->created_at) ? \Carbon\Carbon::parse($cust->created_at)->format('d-m-Y') : '-' }}</td>
                    <td class="text-center position-relative">
                        <button type="button" class="action-dots js-dropdown-toggle">
                            <i class="bi bi-three-dots fs-5"></i>
                        </button>
        
                        <ul class="custom-dropdown-menu">
                            <li>
                                <a class="custom-dropdown-item" href="{{ route('customers.edit', $cust->id) }}">
                                    <i class="bi bi-pencil me-2 text-muted"></i> Edit
                                </a>
                            </li>
                            <li>
                                <button type="button" class="custom-dropdown-item text-danger delete-btn" data-id="{{ $cust->id }}">
                                    <i class="bi bi-trash me-2"></i> Delete
                                </button>
                            </li>
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No Customers Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@if(method_exists($customers, 'links'))
<div class="mt-4">
    {{ $customers->links() }}
</div>
@endif


<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-3 border-0 shadow" style="border-radius: 12px;">
            <div class="modal-body">
                <div class="text-danger mb-2" style="font-size: 40px;">
                    <i class="bi bi-trash3"></i>
                </div>
                <h5 class="fw-bold text-dark">Are you sure?</h5>
                <p class="text-muted" style="font-size: 12px;">You will not be able to recover this Customer</p>
                <form id="deleteForm" method="POST">
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#toggleFilter').on('click', function() {
        $('#filterBox').slideToggle(200);
    });

    
    $('#filterForm input').on('keyup change', function() {
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });

    
    $(document).on('click', '.js-dropdown-toggle', function(e) {
        e.stopPropagation();
        var $menu = $(this).siblings('.custom-dropdown-menu');
        
        
        $('.custom-dropdown-menu').not($menu).removeClass('active');
        
       
        $menu.toggleClass('active');
    });

    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.position-relative').length) {
            $('.custom-dropdown-menu').removeClass('active');
        }
    });

   
    $(document).on('click', '.delete-btn', function() {
        $('.custom-dropdown-menu').removeClass('active');
        let id = $(this).data('id');
        let url = "{{ route('customers.destroy', ':id') }}".replace(':id', id);
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection