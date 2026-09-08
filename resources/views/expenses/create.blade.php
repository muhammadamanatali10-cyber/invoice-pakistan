@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8fafc; }
    .btn-dark-custom { background-color: #0d1b2a; color: #ffffff; border: none; }
    .btn-dark-custom:hover { background-color: #1b263b; color: #ffffff; }
    .form-control-custom {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background-color: #ffffff;
        font-size: 13px;
        height: 38px;
    }
    .form-control-custom:focus { border-color: #cbd5e1; box-shadow: none; }
    .receipt-box {
        border: 2px dashed #cbd5e1 !important;
        border-radius: 8px;
        background-color: #ffffff;
        height: 120px;
    }
</style>

<div class="container-fluid px-4 py-3">
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h3 class="fw-bold text-dark mb-0">New Expense</h3>
            <button type="submit" class="btn btn-dark-custom btn-sm px-3 fw-medium d-flex align-items-center">
                <i class="bi bi-floppy me-1"></i> Save Expense
            </button>
        </div>

       
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}" class="text-decoration-none text-muted">Expenses</a></li>
                <li class="breadcrumb-item active text-secondary">New Expense</li>
            </ol>
        </nav>

        
        <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 8px;">
            <div class="row g-3">
                
                
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">Category <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" class="form-control form-control-custom" placeholder="Enter category" required>
                </div>

                
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" style="border-color: #e2e8f0;"><i class="bi bi-calendar3"></i></span>
                        <input type="date" name="expense_date" class="form-control form-control-custom border-start-0" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

               
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">Amount <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary border-end-0 fw-medium" style="border-color: #e2e8f0; font-size: 13px;">Rs</span>
                        <input type="number" step="0.01" name="amount" class="form-control form-control-custom border-start-0" value="0.00" required>
                    </div>
                </div>

               
                <div class="col-md-6 mt-3">
                    <label class="form-label text-secondary small mb-1">Note</label>
                    <textarea name="notes" class="form-control" rows="5" style="border-color: #e2e8f0; font-size: 13px; border-radius: 6px;" placeholder="Enter expense details..."></textarea>
                </div>

                
                <div class="col-md-6 mt-3">
                    <label class="form-label text-secondary small mb-1">Receipt :</label>
                    <div class="receipt-box p-4 text-center position-relative d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-cloud-upload text-muted fs-3 mb-1"></i>
                        <p class="text-muted mb-0" style="font-size: 12px;">Click here to choose a file</p>
                        <input type="file" name="receipt" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" onchange="document.getElementById('fileNameDisplay').innerText = this.files[0]?.name || ''">
                        <span id="fileNameDisplay" class="d-block text-primary mt-1 fw-medium" style="font-size: 12px;"></span>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection