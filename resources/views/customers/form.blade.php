@extends('layouts.app')

@section('content')
@php
    $isEdit = isset($customer);
@endphp

<form action="{{ $isEdit ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">{{ $isEdit ? 'Edit Customer' : 'New Customer' }}</h4>
            <small class="text-muted">Home / Customers / {{ $isEdit ? 'Edit Customer' : 'New Customer' }}</small>
        </div>
        <button type="submit" class="btn btn-custom-dark btn-sm">
            <i class="fa-solid fa-floppy-disk me-1"></i> {{ $isEdit ? 'Update Customer' : 'Save Customer' }}
        </button>
    </div>

    <div class="card-custom p-4 mb-4">
        <div class="row mb-4">
            <div class="col-md-2">
                <h6 class="fw-bold">Basic Info</h6>
            </div>
            <div class="col-md-10">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $customer->display_name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Primary Contact Name</label>
                        <input type="text" name="primary_contact_name" class="form-control" value="{{ old('primary_contact_name', $customer->primary_contact_name ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Primary Currency</label>
                        <select name="currency" class="form-select">
                            <option value="PKR" {{ old('currency', $customer->currency ?? '') == 'PKR' ? 'selected' : '' }}>PKR - Pakistani Rupee</option>
                            <option value="USD" {{ old('currency', $customer->currency ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="text" name="website" class="form-control" value="{{ old('website', $customer->website ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <hr class="text-muted opacity-25">

        <div class="row my-4">
            <div class="col-md-2">
                <h6 class="fw-bold">Billing Address</h6>
            </div>
            <div class="col-md-10">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" id="billing_name" name="billing_name" class="form-control" value="{{ old('billing_name', $customer->billing_name ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Country</label>
                        <select id="billing_country" name="billing_country" class="form-select">
                            <option value="">Select Country</option>
                            <option value="Pakistan" {{ old('billing_country', $customer->billing_country ?? '') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" id="billing_state" name="billing_state" class="form-control" value="{{ old('billing_state', $customer->billing_state ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" id="billing_city" name="billing_city" class="form-control" value="{{ old('billing_city', $customer->billing_city ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <textarea id="billing_address_1" name="billing_address_1" class="form-control mb-2" rows="2" placeholder="Street 1">{{ old('billing_address_1', $customer->billing_address_1 ?? '') }}</textarea>
                        <textarea id="billing_address_2" name="billing_address_2" class="form-control" rows="2" placeholder="Street 2">{{ old('billing_address_2', $customer->billing_address_2 ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" id="billing_phone" name="billing_phone" class="form-control mb-3" value="{{ old('billing_phone', $customer->billing_phone ?? '') }}">
                        <label class="form-label">Zip Code</label>
                        <input type="text" id="billing_zip_code" name="billing_zip_code" class="form-control" value="{{ old('billing_zip_code', $customer->billing_zip_code ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <hr class="text-muted opacity-25">

        <div class="row mt-4">
            <div class="col-md-2">
                <h6 class="fw-bold">Shipping Address</h6>
            </div>
            <div class="col-md-10">
                <div class="text-end mb-3">
                    <button type="button" class="btn btn-custom-dark btn-sm" id="copyBillingBtn">
                        <i class="fa-regular fa-copy me-1"></i> Copy from Billing
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" id="shipping_name" name="shipping_name" class="form-control" value="{{ old('shipping_name', $customer->shipping_name ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Country</label>
                        <select id="shipping_country" name="shipping_country" class="form-select">
                            <option value="">Select Country</option>
                            <option value="Pakistan" {{ old('shipping_country', $customer->shipping_country ?? '') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" id="shipping_state" name="shipping_state" class="form-control" value="{{ old('shipping_state', $customer->shipping_state ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" id="shipping_city" name="shipping_city" class="form-control" value="{{ old('shipping_city', $customer->shipping_city ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <textarea id="shipping_address_1" name="shipping_address_1" class="form-control mb-2" rows="2" placeholder="Street 1">{{ old('shipping_address_1', $customer->shipping_address_1 ?? '') }}</textarea>
                        <textarea id="shipping_address_2" name="shipping_address_2" class="form-control" rows="2" placeholder="Street 2">{{ old('shipping_address_2', $customer->shipping_address_2 ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" id="shipping_phone" name="shipping_phone" class="form-control mb-3" value="{{ old('shipping_phone', $customer->shipping_phone ?? '') }}">
                        <label class="form-label">Zip Code</label>
                        <input type="text" id="shipping_zip_code" name="shipping_zip_code" class="form-control" value="{{ old('shipping_zip_code', $customer->shipping_zip_code ?? '') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
   
    $('#copyBillingBtn').click(function() {
        $('#shipping_name').val($('#billing_name').val());
        $('#shipping_country').val($('#billing_country').val());
        $('#shipping_state').val($('#billing_state').val());
        $('#shipping_city').val($('#billing_city').val());
        $('#shipping_address_1').val($('#billing_address_1').val());
        $('#shipping_address_2').val($('#billing_address_2').val());
        $('#shipping_phone').val($('#billing_phone').val());
        $('#shipping_zip_code').val($('#billing_zip_code').val());
    });
});
</script>
@endsection