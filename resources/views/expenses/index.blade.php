@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8fafc; }
    .btn-dark-custom { background-color: #0d1b2a; color: #ffffff; border: none; }
    .btn-dark-custom:hover { background-color: #1b263b; color: #ffffff; }
    .filter-card { background-color: #f0f4f8; border-radius: 8px; }
    .form-control-custom, .form-select-custom {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background-color: #ffffff;
        font-size: 13px;
        height: 38px;
    }
    .form-control-custom:focus, .form-select-custom:focus { border-color: #cbd5e1; box-shadow: none; }
    
    .custom-table { 
        background: #ffffff; 
        border-radius: 8px; 
        overflow: visible !important; 
    }
    .custom-table thead th {
        background-color: #f8fafc;
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
    .dropdown-menu-custom {
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        z-index: 1050 !important;
    }
</style>

<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-bold text-dark mb-0">Expenses</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection">
                Filter <i class="bi bi-funnel ms-2 fw-bold"></i>
            </button>
            <a href="{{ route('expenses.create') }}" class="btn btn-dark-custom btn-sm px-3 fw-medium d-flex align-items-center">
                <i class="bi bi-plus-lg me-1"></i> Add Expense
            </a>
        </div>
    </div>

   
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">Expenses</li>
        </ol>
    </nav>

   
    <div class="collapse show mb-4" id="filterSection">
        <div class="filter-card p-3 position-relative">
            <div class="position-absolute" style="top: 12px; right: 20px;">
                <a href="javascript:void(0)" id="clearFilter" class="text-decoration-none text-muted small">Clear All</a>
            </div>

            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">Category</label>
                    <select name="category_id" class="form-select form-select-custom filter-input">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-custom filter-input" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-1">To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-custom filter-input" value="{{ request('to_date') }}">
                </div>
            </form>
        </div>
    </div>

    
    <div class="text-muted small mb-3">
        Showing: <strong id="showingCount" class="text-dark">{{ $expenses->count() }}</strong> of <strong id="totalCount" class="text-dark">{{ $expenses->total() }}</strong>
    </div>

    
    <div class="custom-table shadow-sm" id="tableContainer">
        <table class="table table-borderless align-middle mb-0">
            <thead>
                <tr>
                    <th width="40" class="ps-3"><input type="checkbox" class="form-check-input"></th>
                    <th>CATEGORY</th>
                    <th>EXPENSE DATE</th>
                    <th>NOTE</th>
                    <th>AMOUNT</th>
                    <th width="50" class="text-end pe-3"></th>
                </tr>
            </thead>
            <tbody id="expenseTableBody">
                @forelse($expenses as $expense)
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input"></td>
                    <td>{{ $expense->category->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d-m-Y') }}</td>
                    <td>{{ $expense->notes ?? '-' }}</td>
                    <td class="fw-medium">Rs {{ number_format($expense->amount, 2) }}</td>
                    <td class="text-end pe-3">
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0 border-0" type="button" data-bs-toggle="dropdown" data-bs-boundary="body" aria-expanded="false">
                                <i class="bi bi-three-dots fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom py-2">
                                <li>
                                    <a class="dropdown-item small d-flex align-items-center py-2" href="{{ route('expenses.edit', $expense->id) }}">
                                        <i class="bi bi-pencil me-2 text-muted"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form id="delete-form-{{ $expense->id }}" action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="handleDelete({{ $expense->id }})" class="dropdown-item small text-danger d-flex align-items-center py-2">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No expenses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    
    <div class="mt-3" id="paginationContainer">
        {{ $expenses->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function handleDelete(id) {
    const form = document.getElementById('delete-form-' + id);
    if (!form) return;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            html: `
                <div class="mb-3 mt-2 text-center">
                    <i class="bi bi-trash3-fill" style="font-size: 3.5rem; color: #5c5d88;"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Are you sure?</h4>
                <p class="text-muted small mb-0">You will not be able to recover this Expense</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-4 py-4 px-3',
                confirmButton: 'btn btn-danger btn-sm px-4 fw-medium mx-1',
                cancelButton: 'btn btn-light btn-sm px-4 border fw-medium mx-1'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    } else {
        if (confirm("Are you sure you want to delete this Expense?")) {
            form.submit();
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const filterInputs = document.querySelectorAll('.filter-input');
    const filterForm = document.getElementById('filterForm');
    const clearBtn = document.getElementById('clearFilter');

    function fetchFilteredExpenses(url = "{{ route('expenses.index') }}") {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        fetch(`${url}?${params.toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTable = doc.getElementById('tableContainer');
            const newShowing = doc.getElementById('showingCount');
            const newTotal = doc.getElementById('totalCount');
            const newPagination = doc.getElementById('paginationContainer');

            if (newTable) document.getElementById('tableContainer').innerHTML = newTable.innerHTML;
            if (newShowing) document.getElementById('showingCount').innerText = newShowing.innerText;
            if (newTotal) document.getElementById('totalCount').innerText = newTotal.innerText;
            if (newPagination) document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;

            if (window.bootstrap && bootstrap.Dropdown) {
                var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
                dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
            }
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    filterInputs.forEach(input => {
        input.addEventListener('change', () => fetchFilteredExpenses());
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            filterForm.reset();
            fetchFilteredExpenses();
        });
    }

    const paginationContainer = document.getElementById('paginationContainer');
    if (paginationContainer) {
        paginationContainer.addEventListener('click', function (e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                fetchFilteredExpenses(e.target.href);
            }
        });
    }
});
</script>
@endpush