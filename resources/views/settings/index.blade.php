@extends('layouts.app')

@section('content')
<style>
    .settings-sidebar .nav-link {
        color: #4b5563;
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .settings-sidebar .nav-link:hover { background-color: #f3f4f6; color: #111827; }
    .settings-sidebar .nav-link.active { background-color: #e5e7eb; color: #111827; font-weight: 600; }
    .settings-card { border: none; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .form-label-custom { font-size: 12px; font-weight: 500; color: #374151; margin-bottom: 4px; }
    .btn-save-custom { background-color: #0f172a; color: #fff; font-size: 13px; padding: 6px 18px; border-radius: 4px; border: none; }
    .btn-save-custom:hover { background-color: #1e293b; color: #fff; }
    .avatar-preview { width: 80px; height: 80px; background-color: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .upload-box { border: 1px dashed #d1d5db; border-radius: 6px; padding: 25px; text-align: center; background-color: #fafafa; cursor: pointer; }
</style>

<div class="container-fluid px-4 py-3">
   
    <h4 class="fw-bold mb-1" style="font-size: 18px;">Settings</h4>
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-dark">Settings</li>
        </ol>
    </nav>

    <div class="row g-4">
       
        <div class="col-md-2 settings-sidebar">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <button class="nav-link active text-start" id="account-tab" data-bs-toggle="pill" data-bs-target="#account-settings" type="button">
                    <i class="bi bi-person"></i> Account Settings
                </button>
                <button class="nav-link text-start" id="company-tab" data-bs-toggle="pill" data-bs-target="#company-info" type="button">
                    <i class="bi bi-building"></i> Company Information
                </button>
                <a href="#" class="nav-link"><i class="bi bi-pencil-square"></i> Customization</a>
                <a href="#" class="nav-link"><i class="bi bi-gear"></i> Preferences</a>
                <a href="#" class="nav-link"><i class="bi bi-check-circle"></i> Tax Types</a>
                <a href="#" class="nav-link"><i class="bi bi-list-task"></i> Expense Categories</a>
                <a href="#" class="nav-link"><i class="bi bi-bell"></i> Notifications</a>
            </div>
        </div>

       
        <div class="col-md-10">
            <div class="tab-content" id="v-pills-tabContent">
                
                
                <div class="tab-pane fade show active" id="account-settings">
                    <div class="card settings-card p-4 bg-white">
                        <h6 class="fw-bold mb-1" style="font-size: 15px;">Account Settings</h6>
                        <p class="text-muted mb-4" style="font-size: 12px;">You can update your name, email & password using the form below.</p>

                        @if(session('success'))
                            <div class="alert alert-success py-2" style="font-size:12px;">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger py-2" style="font-size:12px;">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('settings.account.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label-custom d-block">Profile Picture</label>
                                <div class="avatar-preview" id="profileUploadBox" style="cursor: pointer;">
                                    @if(auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-fill text-secondary" style="font-size: 48px;"></i>
                                    @endif
                                </div>
                                <input type="file" name="profile_picture" class="d-none" id="profileInput" accept="image/*">
                                <div class="text-muted mt-1" style="font-size: 11px;">Click box to choose a picture</div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Name</label>
                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', auth()->user()->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Email</label>
                                    <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Password</label>
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Confirm Password">
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-save-custom"><i class="bi bi-floppy me-1"></i> Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="company-info">
                    <div class="card settings-card p-4 bg-white">
                        <h6 class="fw-bold mb-1" style="font-size: 15px;">Company info</h6>
                        <p class="text-muted mb-4" style="font-size: 12px;">Information about your company that will be displayed on invoices, estimates and other documents.</p>

                        <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label-custom">Company Logo</label>
                                <div class="upload-box" id="logoUploadBox">
                                    @if(isset($company) && $company->company_logo)
                                        <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo" style="max-height: 50px; object-fit: contain;">
                                    @else
                                        <i class="bi bi-cloud-arrow-up text-muted fs-4"></i>
                                        <div class="text-muted mt-1" style="font-size: 11px;">Click here to choose a file</div>
                                    @endif
                                </div>
                                <input type="file" name="company_logo" class="d-none" id="logoUpload" accept="image/*">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control form-control-sm" value="{{ old('company_name', $company->company_name ?? 'Sublime Logics') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Phone</label>
                                    <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $company->phone ?? '') }}" placeholder="Phone">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Country <span class="text-danger">*</span></label>
                                    <select name="country" class="form-select form-select-sm">
                                        <option value="Pakistan" {{ (isset($company) && $company->country == 'Pakistan') ? 'selected' : '' }}>Pakistan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">State</label>
                                    <input type="text" name="state" class="form-control form-control-sm" value="{{ old('state', $company->state ?? '') }}" placeholder="State">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">City</label>
                                    <input type="text" name="city" class="form-control form-control-sm" value="{{ old('city', $company->city ?? '') }}" placeholder="City">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Zip</label>
                                    <input type="text" name="zip" class="form-control form-control-sm" value="{{ old('zip', $company->zip ?? '') }}" placeholder="Zip">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Address</label>
                                <input type="text" name="address_line1" class="form-control form-control-sm mb-2" value="{{ old('address_line1', $company->address_line1 ?? '') }}" placeholder="Street 1">
                                <input type="text" name="address_line2" class="form-control form-control-sm" value="{{ old('address_line2', $company->address_line2 ?? '') }}" placeholder="Street 2">
                            </div>

                            <div>
                                <button type="submit" class="btn btn-save-custom"><i class="bi bi-floppy me-1"></i> Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    
    document.getElementById('logoUploadBox').addEventListener('click', function() {
        document.getElementById('logoUpload').click();
    });

    
    document.getElementById('profileUploadBox').addEventListener('click', function() {
        document.getElementById('profileInput').click();
    });

    document.getElementById('profileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewBox = document.getElementById('profileUploadBox');
                previewBox.innerHTML = `<img src="${e.target.result}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection