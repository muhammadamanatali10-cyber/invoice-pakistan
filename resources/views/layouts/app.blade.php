<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Invoice Pakistan</title>
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #0b2239; min-width: 240px; max-width: 240px; min-height: 100vh; }
        .sidebar .nav-link { color: #a0aec0; border-radius: 6px; padding: 10px 15px; margin-bottom: 2px; }
        .sidebar .nav-link:hover { color: #ffffff; background-color: rgba(255, 255, 255, 0.08); }
        .sidebar .nav-link.active { color: #0b2239; background-color: #ffffff; font-weight: 600; }
        .navbar-custom { background-color: #0b2239; }
    </style>
</head>
<body class="d-flex">

    
    <aside class="sidebar d-flex flex-column p-3">
       
        <div class="d-flex align-items-center text-white fs-5 fw-bold px-2 py-3 border-bottom border-secondary mb-3">
            <img src="{{ asset('images/logo-white.png') }}" alt="Invoice Pakistan Logo" class="logo" style="max-height: 40px;">
        </div>

        
        <div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="{{ url('/dashboard') }}" class="nav-link menu-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li><a href="{{ route('customers.index') }}" class="nav-link menu-link"><i class="bi bi-person me-2"></i> Customers</a></li>
                <li><a href="{{ url('/suppliers') }}" class="nav-link menu-link"><i class="bi bi-people me-2"></i> Suppliers</a></li>
                <li><a href="{{ url('/items') }}" class="nav-link menu-link"><i class="bi bi-star me-2"></i> Items</a></li>
                <hr class="text-secondary my-2">
                <li><a href="{{ url('/estimates') }}" class="nav-link menu-link"><i class="bi bi-file-earmark-text me-2"></i> Estimates</a></li>
                <li><a href="{{ url('/invoices') }}" class="nav-link menu-link"><i class="bi bi-file-earmark-spreadsheet me-2"></i> Invoices</a></li>
                <li><a href="{{ url('/payments') }}" class="nav-link menu-link"><i class="bi bi-credit-card me-2"></i> Payments</a></li>
                <li><a href="{{ url('/expenses') }}" class="nav-link menu-link"><i class="bi bi-receipt-cutoff me-2"></i> Expenses</a></li>
                <hr class="text-secondary my-2">
                <li><a href="{{ url('/payables') }}" class="nav-link menu-link"><i class="bi bi-wallet2 me-2"></i> Payables</a></li>
                <li><a href="{{ url('/reports') }}" class="nav-link menu-link"><i class="bi bi-bar-chart-line me-2"></i> Reports</a></li>
                <li><a href="{{ url('/settings') }}" class="nav-link menu-link"><i class="bi bi-gear me-2"></i> Settings</a></li>
            </ul>
        </div>
    </aside>

    
    <div class="d-flex flex-column flex-grow-1 min-vh-100 justify-content-between">
        <div>
          
            <nav class="navbar navbar-custom px-4 py-2 position-relative">
                <div class="ms-auto d-flex align-items-center gap-3">
                   
                    <div class="position-relative">
                        <button id="quickAddBtn" class="btn button p-0 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="bi bi-plus-lg fs-5"></i>
                        </button>
                        <div id="quickAddMenu" class="dropdown-menu shadow border-0 p-2 position-absolute end-0 mt-2" style="display: none; min-width: 180px; z-index: 1050;">
                            <a class="dropdown-item py-2" href="{{ url('/estimates/create') }}"><i class="bi bi-file-earmark-text me-2"></i> New Estimate</a>
                            <a class="dropdown-item py-2" href="{{ url('/invoices/create') }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i> New Invoice</a>
                            <a class="dropdown-item py-2" href="{{ route('customers.create') }}"><i class="bi bi-person me-2"></i> New Customer</a>
                        </div>
                    </div>

                    
                    <div class="position-relative">
    <div id="profileBtn" class="bg-secondary d-flex align-items-center justify-content-center text-white overflow-hidden rounded cursor-pointer" style="width: 35px; height: 35px; cursor: pointer;">
        @if(auth()->user() && auth()->user()->profile_picture)
            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="avatar" height="35px" width="35px" style="object-fit: cover;">
        @else
            <img src="{{ asset('images/default-avatar.jpg') }}" alt="avatar" height="35px" width="35px" style="object-fit: cover;">
        @endif
    </div>
    <div id="profileMenu" class="dropdown-menu shadow border-0 p-2 position-absolute end-0 mt-2" style="display: none; min-width: 150px; z-index: 1050;">
        <a class="dropdown-item py-2" href="{{ url('/settings') }}"><i class="bi bi-gear me-2"></i> Settings</a>
        
        
        <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="m-0">
            @csrf
            <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
                </div>
            </nav>

            
            <main class="p-4" id="main-content">
                @yield('content')
            </main>
        </div>

        
        <footer class="bg-white text-end py-2 px-4 border-top text-muted small">
            Powered by <strong class="text-primary">Amanat Ali</strong>
        </footer>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            
            $('#quickAddBtn').on('click', function(e) {
                e.stopPropagation();
                $('#profileMenu').hide();
                $('#quickAddMenu').stop(true, true).slideToggle(150);
            });

           
            $('#profileBtn').on('click', function(e) {
                e.stopPropagation();
                $('#quickAddMenu').hide();
                $('#profileMenu').stop(true, true).slideToggle(150);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#quickAddMenu, #quickAddBtn, #profileMenu, #profileBtn').length) {
                    $('#quickAddMenu, #profileMenu').fadeOut(100);
                }
            });

           
            var currentUrl = window.location.href;

            $('.menu-link').each(function() {
                var linkUrl = $(this).attr('href');

                if (linkUrl && linkUrl !== '#' && currentUrl.indexOf(linkUrl) !== -1) {
                    $('.menu-link').removeClass('active');
                    $(this).addClass('active');
                }
            });

            
            $(document).on('click', '.menu-link', function(e) {
                var href = $(this).attr('href');

                if (href && href !== '#' && href !== 'javascript:void(0);') {
                    window.location.href = href;
                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
    
    
    <script src="{{ asset('js/custom-ajax.js') }}"></script>
    @yield('scripts')
</body>
</html>