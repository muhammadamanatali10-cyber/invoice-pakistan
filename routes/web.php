<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('items', ItemController::class);
    Route::post('/item-units', [ItemController::class, 'storeUnit'])->name('item-units.store');

    Route::resource('customers', CustomerController::class);

    Route::resource('estimates', EstimateController::class);
    Route::prefix('estimates')->name('estimates.')->group(function () {
        Route::patch('{estimate}/mark-as-sent', [EstimateController::class, 'markAsSent'])->name('markAsSent');
        Route::patch('{estimate}/mark-as-rejected', [EstimateController::class, 'markAsRejected'])->name('markAsRejected');
        Route::post('{estimate}/convert-to-invoice', [EstimateController::class, 'convertToInvoice'])->name('convertToInvoice');
    });

    Route::resource('invoices', InvoiceController::class);

    Route::resource('payments', PaymentController::class);
    Route::get('get-customer-invoices/{customerId}', [PaymentController::class, 'getCustomerInvoices'])->name('payments.getCustomerInvoices');

    Route::resource('expenses', ExpenseController::class);
    Route::post('expense-categories/quick-store', [ExpenseController::class, 'storeCategory'])->name('expense-categories.quickStore');

   
Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('suppliers/invite', [SupplierController::class, 'create'])->name('suppliers.invite');
Route::post('suppliers/invite', [SupplierController::class, 'store'])->name('suppliers.sendInvite');
Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('payables', [PayableController::class, 'index'])->name('payables.index');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/download-pdf', [ReportController::class, 'downloadPdf'])->name('reports.downloadPdf');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account.update');
    Route::post('/settings/company', [SettingsController::class, 'updateCompany'])->name('settings.company.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});



Route::post('/estimates/{id}/convert', [EstimateController::class, 'convertToInvoice'])->name('estimates.convert');
Route::patch('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid');