@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8fafc; }
    
   
    .filter-card { 
        background-color: #f0f4f8; 
        border-radius: 8px; 
    }
    .form-control-custom, .form-select-custom {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background-color: #ffffff;
        font-size: 13px;
        height: 38px;
    }
    .form-control-custom:focus, .form-select-custom:focus { 
        border-color: #cbd5e1; 
        box-shadow: none; 
    }

    
    .custom-tabs {
        border-bottom: 1px solid #e2e8f0;
    }
    .custom-tabs .nav-link {
        color: #64748b;
        font-weight: 500;
        font-size: 13px;
        padding: 8px 16px;
        border: none;
        border-bottom: 2px solid transparent;
        background: transparent;
    }
    .custom-tabs .nav-link.active {
        color: #0f172a;
        font-weight: 600;
        border-bottom: 2px solid #0f172a;
        background: transparent;
    }

    
    .custom-table { 
        background: #ffffff; 
        border-radius: 8px; 
        overflow: visible !important; 
    }
    .custom-table thead th {
        background-color: #ffffff;
        color: #1e293b;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f5f9;
        padding-top: 14px;
        padding-bottom: 14px;
    }
    .custom-table tbody td {
        padding-top: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f8fafc;
        color: #334155;
        font-size: 13px;
    }

    
    .badge-soft-warning {
        background-color: #fef3c7;
        color: #d97706;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    .badge-soft-success {
        background-color: #dcfce7;
        color: #15803d;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    .dropdown-menu-custom {
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }
</style>

<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-bold text-dark mb-0">Payables</h3>
        <div>
            <button class="btn btn-outline-secondary btn-sm px-3 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection">
                Filter <i class="bi bi-x-lg ms-2"></i>
            </button>
        </div>
    </div>

    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">Payables</li>
        </ol>
    </nav>

    
    <div class="collapse show mb-4" id="filterSection">
        <div class="filter-card p-3 position-relative">
            <div class="position-absolute" style="top: 12px; right: 20px;">
                <a href="{{ route('payables.index', ['tab' => $tab]) }}" class="text-decoration-none text-muted small">Clear All</a>
            </div>

            <form method="GET" action="{{ route('payables.index') }}" class="row g-3 align-items-end">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="col-md-2">
                    <label class="form-label text-secondary small mb-1">Suppliers</label>
                    <select name="supplier_id" class="form-select form-select-custom" onchange="this.form.submit()">
                        <option value="">Type or click to select</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-secondary small mb-1">Status</label>
                    <select name="status" class="form-select form-select-custom" onchange="this.form.submit()">
                        <option value="">Select Status</option>
                        <option value="UNPAID" {{ (request('status') == 'UNPAID' || $tab == 'unpaid') ? 'selected' : '' }}>UNPAID</option>
                        <option value="PAID" {{ (request('status') == 'PAID' || $tab == 'paid') ? 'selected' : '' }}>PAID</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-secondary small mb-1">From</label>
                    <input type="date" name="from_date" class="form-control form-control-custom" value="{{ request('from_date') }}" onchange="this.form.submit()">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-secondary small mb-1">To</label>
                    <input type="date" name="to_date" class="form-control form-control-custom" value="{{ request('to_date') }}" onchange="this.form.submit()">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">Invoice Number</label>
                    <input type="text" name="invoice_number" class="form-control form-control-custom" placeholder="#" value="{{ request('invoice_number') }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    
    <ul class="nav custom-tabs mb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'unpaid' ? 'active' : '' }}" href="{{ route('payables.index', array_merge(request()->query(), ['tab' => 'unpaid'])) }}">Unpaid</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'paid' ? 'active' : '' }}" href="{{ route('payables.index', array_merge(request()->query(), ['tab' => 'paid'])) }}">Paid</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'all' ? 'active' : '' }}" href="{{ route('payables.index', array_merge(request()->query(), ['tab' => 'all'])) }}">All</a>
        </li>
    </ul>

    
    <div class="text-end text-muted small mb-3">
        Showing: <strong class="text-dark">{{ $payables->count() }}</strong> of <strong class="text-dark">{{ $payables->total() }}</strong>
    </div>

    
    <div class="custom-table shadow-sm">
        <table class="table table-borderless align-middle mb-0">
            <thead>
                <tr>
                    <th width="40" class="ps-3"><input type="checkbox" class="form-check-input"></th>
                    <th>DATE</th>
                    <th>SUPPLIER</th>
                    <th>STATUS</th>
                    <th>PAID STATUS</th>
                    <th>NUMBER</th>
                    <th>AMOUNT DUE</th>
                    <th width="50" class="text-end pe-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payables as $payable)
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input"></td>
                    <td>{{ \Carbon\Carbon::parse($payable->date)->format('d-m-Y') }}</td>
                    <td>{{ $payable->supplier->name ?? 'N/A' }}</td>
                    <td><span class="badge badge-soft-warning">{{ $payable->status }}</span></td>
                    <td>
                        <span class="badge {{ $payable->paid_status == 'PAID' ? 'badge-soft-success' : 'badge-soft-warning' }}">
                            {{ $payable->paid_status }}
                        </span>
                    </td>
                    <td>{{ $payable->invoice_number }}</td>
                    <td class="fw-medium">Rs {{ number_format($payable->amount_due, 2) }}</td>
                    <td class="text-end pe-3">
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom py-2">
                                <li>
                                    <a class="dropdown-item small d-flex align-items-center py-2" href="#">
                                        <i class="bi bi-eye me-2 text-muted"></i> View
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">There are no matching rows</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    
    <div class="mt-3">
        {{ $payables->links() }}
    </div>
</div>
@endsection