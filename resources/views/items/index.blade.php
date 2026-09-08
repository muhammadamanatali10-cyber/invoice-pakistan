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
    .filter-card .form-control {
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
        <h3 class="page-title mb-1">Items</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    <a href="{{ route('items.index') }}" class="text-decoration-none text-muted">Items</a>
                </li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-filter d-flex align-items-center gap-2" id="toggleFilter">
            Filter <i class="bi bi-x-lg fw-bold" id="filterIcon" style="font-size: 12px;"></i>
        </button>
        <a href="{{ route('items.create') }}" class="btn btn-primary-custom d-flex align-items-center gap-2 text-decoration-none">
            <i class="bi bi-plus-lg"></i> Add Item
        </a>
    </div>
</div>


<div class="filter-card p-4 mb-4" id="filterBox">
    <form method="GET" action="{{ route('items.index') }}" id="filterForm">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label mb-2">Name</label>
                <input type="text" name="name" class="form-control filter-input" value="{{ request('name') }}" placeholder="Search name...">
            </div>
            
            
            <div class="col-md-4">
                <label class="form-label mb-2">Unit</label>
                <input type="text" name="unit" class="form-control filter-input" value="{{ request('unit') }}" placeholder="Search unit...">
            </div>

            <div class="col-md-4">
                <label class="form-label mb-2">Price</label>
                <input type="text" name="price" class="form-control filter-input" value="{{ request('price') }}" placeholder="Search price...">
            </div>
        </div>
        <div class="text-end mt-2">
            <a href="{{ route('items.index') }}" class="text-decoration-none clear-btn">Clear All</a>
        </div>
    </form>
</div>


<p class="text-muted small mb-3 ms-1" style="font-size: 13px;">Showing: <b>{{ $items->count() }} of {{ $items->total() }}</b></p>


<div class="table-container-card p-2">
    <div class="table-responsive">
        <table class="table custom-table align-middle mb-0">
            <thead>
                <tr class="text-uppercase">
                    <th width="50" class="ps-3"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                    <th>NAME</th>
                    <th>UNIT</th>
                    <th>PRICE</th>
                    <th>ADDED ON</th>
                    <th width="60" class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input item-checkbox"></td>
                    <td>{{ $item->name }}</td>
                    <td class="text-muted">{{ $item->unit ?? 'N/A' }}</td>
                    <td><strong>Rs</strong> {{ number_format($item->price, 2) }}</td>
                    <td class="text-muted">{{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}</td>
                    <td class="text-center position-relative">
                        <button type="button" class="action-dots js-dropdown-toggle">
                            <i class="bi bi-three-dots fs-5"></i>
                        </button>
                        <ul class="custom-dropdown-menu">
                            <li>
                                <button type="button" class="custom-dropdown-item text-danger delete-btn" data-id="{{ $item->id }}">
                                    <i class="bi bi-trash me-2"></i> Delete
                                </button>
                            </li>
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No items found.</td>
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
                <p class="text-muted small">You will not be able to recover this item</p>
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
                if (filterIcon) {
                    filterIcon.className = 'bi bi-x-lg fw-bold';
                }
            } else {
                filterBox.style.display = 'none';
                if (filterIcon) {
                    filterIcon.className = 'bi bi-funnel-fill fw-bold';
                }
            }
        });
    }

    
    let timeout = null;
    document.querySelectorAll('.filter-input').forEach(function(input) {
        input.addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                filterForm.submit();
            }, 500);
        });
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
                deleteForm.action = "{{ url('items') }}/" + itemId;
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