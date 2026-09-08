@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8fafc; }

    
    .custom-report-tabs {
        border-bottom: 1px solid #e2e8f0;
    }
    .custom-report-tabs .nav-link {
        color: #64748b;
        font-weight: 500;
        font-size: 14px;
        padding: 8px 16px;
        border: none;
        border-bottom: 2px solid transparent;
        background: transparent;
    }
    .custom-report-tabs .nav-link.active {
        color: #0f172a;
        font-weight: 600;
        border-bottom: 2px solid #0f172a;
        background: transparent;
    }

    
    .form-control-custom, .form-select-custom {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background-color: #ffffff;
        font-size: 13px;
        height: 38px;
    }
    .form-control-custom:focus, .form-select-custom:focus { 
        border-color: #94a3b8; 
        box-shadow: none; 
    }

    
    .pdf-container {
        background-color: #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        min-height: 650px;
    }
    .pdf-viewer-frame {
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    
    .btn-dark-custom {
        background-color: #0d1b2a;
        color: #ffffff;
        border: none;
    }
    .btn-dark-custom:hover {
        background-color: #1b263b;
        color: #ffffff;
    }
</style>

<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-bold text-dark mb-0">Reports</h3>
        <a href="{{ route('reports.downloadPdf', request()->query()) }}" class="btn btn-dark-custom btn-sm px-3 fw-medium d-flex align-items-center">
            <i class="bi bi-download me-2"></i> Download PDF
        </a>
    </div>

    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">Reports</li>
        </ol>
    </nav>

    
    <ul class="nav custom-report-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'sales' ? 'active' : '' }}" href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'sales'])) }}">Sales</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'profit_loss' ? 'active' : '' }}" href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'profit_loss'])) }}">Profit & Loss</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'expenses' ? 'active' : '' }}" href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'expenses'])) }}">Expenses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'taxes' ? 'active' : '' }}" href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'taxes'])) }}">Taxes</a>
        </li>
    </ul>

    <div class="row g-4">
       
        <div class="col-md-3">
            <form method="GET" action="{{ route('reports.index') }}" id="reportFilterForm">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="mb-3">
                    <label class="form-label text-secondary small mb-1">Select Date Range</label>
                    <select name="date_range" class="form-select form-select-custom" onchange="this.form.submit()">
                        <option value="this_month" {{ $dateRange == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small mb-1">From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-custom" value="{{ $fromDate }}">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small mb-1">To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-custom" value="{{ $toDate }}">
                </div>

                @if($tab == 'sales')
                <div class="mb-3">
                    <label class="form-label text-secondary small mb-1">Report Type</label>
                    <select name="report_type" class="form-select form-select-custom">
                        <option value="by_customer" {{ $reportType == 'by_customer' ? 'selected' : '' }}>By Customer</option>
                    </select>
                </div>
                @endif

                <button type="submit" class="btn btn-outline-dark btn-sm w-100 py-2 fw-medium mt-2">Update Report</button>
            </form>
        </div>

        
        <div class="col-md-9">
            <div class="pdf-container d-flex align-items-center justify-content-center">
                <iframe src="{{ route('reports.downloadPdf', array_merge(request()->query(), ['preview' => 1])) }}" width="100%" height="650px" class="pdf-viewer-frame border-0"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection