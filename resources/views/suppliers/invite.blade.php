@extends('layouts.app')

@section('content')
<h4 class="fw-bold mb-1">Invite Supplier</h4>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}" class="text-decoration-none text-muted">Suppliers</a></li>
        <li class="breadcrumb-item active">Invite Supplier</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm p-4">
    <form action="{{ route('suppliers.sendInvite') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-muted small mb-1">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" placeholder="Invitee Email" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-dark btn-sm px-3">
            <i class="bi bi-send me-1"></i> Send Invitation
        </button>
    </form>
</div>
@endsection