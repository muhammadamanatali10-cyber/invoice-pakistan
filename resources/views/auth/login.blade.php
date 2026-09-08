<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Invoice Pakistan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { 
            height: 100vh; 
            margin: 0; 
            overflow: hidden; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background-color: #ffffff; 
        }
        .left-panel { 
            padding: 2.5rem 3rem; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            height: 100vh; 
        }
        .right-panel { 
            background-color: #3b76bb; 
            color: #ffffff; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            padding: 3rem; 
            text-align: center; 
            height: 100vh; 
        }
        .logo-text { font-weight: 800; color: #0f172a; letter-spacing: -0.5px; font-size: 22px; line-height: 1.1; }
        .form-label-custom { font-size: 13px; color: #4b5563; font-weight: 500; margin-bottom: 4px; }
        .form-control-custom { border: 1px solid #e5e7eb; border-radius: 4px; padding: 7px 12px; font-size: 14px; }
        .form-control-custom:focus { border-color: #3b76bb; box-shadow: 0 0 0 2px rgba(59, 118, 187, 0.15); }
        .btn-login { background-color: #0d233a; color: #ffffff; border: none; padding: 8px 24px; border-radius: 4px; font-size: 14px; font-weight: 500; width: 100%; }
        .btn-login:hover { background-color: #1a365d; color: #ffffff; }
        .copyright-text { color: #9ca3af; font-size: 12px; }
        .banner-heading { font-size: 38px; font-weight: 700; line-height: 1.15; max-width: 500px; margin-bottom: 20px; }
        .banner-subtext { font-size: 14px; opacity: 0.9; max-width: 500px; line-height: 1.5; font-weight: 300; }

        
        @media (max-width: 991.98px) {
            body, html { height: auto; overflow: auto; }
            .left-panel { height: auto; min-height: 100vh; padding: 2rem 1.5rem; justify-content: space-between; align-items: center; }
            .left-panel > div:nth-child(2) { width: 100%; max-width: 320px; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0 h-100">
    <div class="row g-0 h-100">
        
        <div class="col-lg-5 col-md-12 left-panel">
           
            <div class="d-flex align-items-center gap-2">
            
                
            </div>

           
            <div class="w-100" style="max-width: 320px;">
                @if($errors->any())
                    <div class="alert alert-danger py-2 mb-3" style="font-size: 12px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                     <div class="d-flex align-items-center  mb-4">
            <img src="{{ asset('images/app-logo.png') }}" alt="Invoice Pakistan Logo" class="logo" style="max-height: 60px;">
                 </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Email or Username <span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control form-control-custom" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-custom" required>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size: 13px; color: #4b5563;">
                            Remember Me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">Login</button>
                </form>
            </div>

            <div class="d-flex justify-content-between w-100 copyright-text">
                <span>Copyright &copy; AmanatAli - 2026</span>
                <span>Powered by <strong class="text-primary">Amanat Ali</strong></span>
            </div>
        </div>

        <div class="col-lg-7 d-none d-lg-flex right-panel">
            <h1 class="banner-heading">
                Super Simple Invoicing<br>for Freelancers &<br>Small Businesses
            </h1>
            <p class="banner-subtext">
                Invoice Pakistan helps you track expenses, record payments & generate beautiful invoices & estimates with ability to choose multiple templates.
            </p>
        </div>
    </div>
</div>

</body>
</html>