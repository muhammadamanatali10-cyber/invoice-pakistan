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
    .form-card {
        background-color: #ffffff;
        border-radius: 12px;
        border: 1px solid #f0f4f8;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .form-card label, .modal-body label {
        font-size: 13px;
        font-weight: 500;
        color: #334155;
    }
    .form-card .form-control, 
    .form-card .form-select,
    .modal-body .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        height: 42px;
        box-shadow: none;
        color: #334155;
        font-size: 14px;
        background-color: #ffffff;
    }
    .form-card textarea.form-control {
        height: auto !important;
        border-radius: 8px;
    }
    .form-card .form-control:focus, 
    .form-card .form-select:focus,
    .modal-body .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .input-group-text-custom {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-right: none;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
        color: #475569;
        font-size: 14px;
        padding-left: 14px;
        padding-right: 14px;
    }
    .price-input {
        border-left: none !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .btn-save-item {
        background-color: #0b2239;
        color: #ffffff;
        font-weight: 500;
        border-radius: 6px;
        padding: 9px 24px;
        font-size: 14px;
        border: none;
        transition: all 0.2s;
    }
    .btn-save-item:hover {
        background-color: #143252;
        color: #ffffff;
    }
    
    .custom-modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        padding: 8px;
    }
    .custom-modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
    }
    .custom-modal-title {
        font-weight: 700;
        color: #0c1523;
        font-size: 1.1rem;
    }
    .btn-cancel-modal {
        border: 1px solid #0b2239;
        color: #0b2239;
        background: #ffffff;
        font-weight: 500;
        border-radius: 6px;
        padding: 7px 20px;
        font-size: 14px;
    }
    .btn-cancel-modal:hover {
        background: #f8fafc;
        color: #0b2239;
    }
</style>


<div class="mb-4">
    <h3 class="page-title mb-1">New Item</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('items.index') }}" class="text-decoration-none text-muted">Items</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">New Item</li>
        </ol>
    </nav>
</div>


<div class="form-card p-4 mb-4">
    <form action="{{ route('items.store') }}" method="POST" id="createItemForm">
        @csrf
        <div class="row g-4 mb-4">
           
            <div class="col-md-4">
                <label class="form-label mb-2">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

           
            <div class="col-md-4">
                <label class="form-label mb-2">Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom">Rs</span>
                    <input type="text" class="form-control price-input @error('price') is-invalid @enderror" name="price" value="{{ old('price', '0.00') }}" required>
                </div>
                @error('price')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            
            <div class="col-md-4">
                <label class="form-label mb-2">Unit</label>
                <select class="form-select @error('unit') is-invalid @enderror" name="unit" id="unitSelect">
                    <option value="" selected disabled>select unit</option>
                    <option value="Pounds" {{ old('unit') == 'Pounds' ? 'selected' : '' }}>Pounds</option>
                    <option value="Kg" {{ old('unit') == 'Kg' ? 'selected' : '' }}>Kg</option>
                    <option value="Hours" {{ old('unit') == 'Hours' ? 'selected' : '' }}>Hours</option>
                    <option value="Pieces" {{ old('unit') == 'Pieces' ? 'selected' : '' }}>Pieces</option>
                    <option value="add_new_unit" class="fw-bold text-primary">+ Add New Unit</option>
                </select>
                @error('unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

           
            <div class="col-12">
                <label class="form-label mb-2">Description</label>
                <textarea class="form-control" name="description" rows="5">{{ old('description') }}</textarea>
            </div>
        </div>

       
        <div class="text-center pt-2">
            <button type="submit" class="btn btn-save-item d-inline-flex align-items-center gap-2">
                <i class="bi bi-save-fill"></i> Save Item
            </button>
        </div>
    </form>
</div>


<div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content custom-modal-content">
            <div class="custom-modal-header d-flex justify-content-between align-items-center">
                <h5 class="custom-modal-title mb-0">Add Item Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addUnitForm">
                    @csrf
                    <div class="row align-items-center mb-4">
                        <label class="col-sm-3 col-form-label text-sm-end pe-0">Unit Name<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="newUnitName" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-save-item d-inline-flex align-items-center gap-2" id="saveUnitBtn">
                            <i class="bi bi-save-fill"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const unitSelect = document.getElementById('unitSelect');
    const unitModalElement = document.getElementById('unitModal');
    let unitModalInstance = null;

    if (typeof bootstrap !== 'undefined' && unitModalElement) {
        unitModalInstance = new bootstrap.Modal(unitModalElement);
    }

    
    if (unitSelect) {
        unitSelect.addEventListener('change', function () {
            if (this.value === 'add_new_unit') {
                if (unitModalInstance) {
                    unitModalInstance.show();
                }
                this.value = ''; 
            }
        });
    }

   
    const saveUnitBtn = document.getElementById('saveUnitBtn');
    if (saveUnitBtn) {
        saveUnitBtn.addEventListener('click', function () {
            const unitInput = document.getElementById('newUnitName');
            const unitName = unitInput ? unitInput.value.trim() : '';

            if (!unitName) {
                alert('Please enter a unit name');
                return;
            }

            
            const newOption = document.createElement('option');
            newOption.value = unitName;
            newOption.textContent = unitName;
            newOption.selected = true;

           
            const addNewOption = unitSelect.querySelector('option[value="add_new_unit"]');
            unitSelect.insertBefore(newOption, addNewOption);

            
            unitInput.value = '';
            if (unitModalInstance) {
                unitModalInstance.hide();
            }
        });
    }
});
</script>
@endsection