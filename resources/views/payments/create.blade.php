@extends('layouts.app')

@section('content')


@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <strong>Validation Failed:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('payments.store') }}" method="POST">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 class="fw-bold mb-0">New Payment</h4>
        <button type="submit" class="btn btn-dark btn-sm px-3">
            <i class="bi bi-floppy me-1"></i> Save Payment
        </button>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payments.index') }}" class="text-decoration-none text-muted">Payments</a></li>
            <li class="breadcrumb-item active">New Payment</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm p-4">
        <div class="row g-3">
            
            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">Date <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">Payment Number <span class="text-danger">*</span></label>
                <input type="text" name="payment_number" class="form-control form-control-sm bg-light" value="{{ old('payment_number', $nextPaymentNum ?? '') }}" readonly required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">Customer <span class="text-danger">*</span></label>
                <select name="customer_id" id="customerSelect" class="form-select form-select-sm" required>
                    <option value="">Select a customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">Invoice</label>
                <select name="invoice_id" id="invoiceSelect" class="form-select form-select-sm">
                    <option value="">Select Invoice</option>
                    @if(isset($invoices) && count($invoices) > 0)
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" data-customer="{{ $invoice->customer_id }}" data-amount="{{ (float)$invoice->total_amount }}">
                                {{ $invoice->invoice_number }} (Rs {{ number_format((float)$invoice->total_amount, 2) }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">Amount <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted fw-bold">Rs</span>
                    <input type="number" step="0.01" name="amount" id="amountInput" class="form-control" value="{{ old('amount', '0.00') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">Payment Mode</label>
                <select name="payment_mode" class="form-select form-select-sm">
                    <option value="">Select payment mode</option>
                    <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="Online" {{ old('payment_mode') == 'Online' ? 'selected' : '' }}>Online</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label class="form-label text-muted small mb-1">Note</label>
                <textarea name="notes" class="form-control form-control-sm" rows="4" placeholder="Enter any extra details...">{{ old('notes') }}</textarea>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customerSelect');
    const invoiceSelect = document.getElementById('invoiceSelect');
    const amountInput = document.getElementById('amountInput');

    let isInternalChange = false;

   
    customerSelect.addEventListener('change', function() {
        if (isInternalChange) return;
        const customerId = this.value;

        invoiceSelect.innerHTML = '<option value="">Select Invoice</option>';
        amountInput.value = '0.00';

        if (customerId) {
            fetch(`{{ url('get-customer-invoices') }}/${customerId}`)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Select Invoice</option>';

                    if (data && data.length > 0) {
                        data.forEach(invoice => {
                            let amount = parseFloat(invoice.total_amount) || 0;
                            options += `<option value="${invoice.id}" data-customer="${invoice.customer_id}" data-amount="${amount}">
                                ${invoice.invoice_number} (Rs ${amount.toFixed(2)})
                            </option>`;
                        });
                    } else {
                        options = '<option value="">No Invoices Found</option>';
                    }

                    invoiceSelect.innerHTML = options;
                })
                .catch(error => console.error('Error fetching invoices:', error));
        }
    });

   
    invoiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption && selectedOption.value !== "") {
            const customerId = selectedOption.getAttribute('data-customer');
            const amountVal = parseFloat(selectedOption.getAttribute('data-amount')) || 0;

            if (customerId && customerSelect.value !== customerId) {
                isInternalChange = true;
                customerSelect.value = customerId;
                setTimeout(() => { isInternalChange = false; }, 200);
            }

            amountInput.value = amountVal > 0 ? amountVal.toFixed(2) : '0.00';
        } else {
            amountInput.value = '0.00';
        }
    });
});
</script>
@endpush