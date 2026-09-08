<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        
        $totalAmountDue = Schema::hasColumn('invoices', 'amount_due') 
            ? Invoice::where('status', '!=', 'PAID')->sum('amount_due') 
            : 0;

        $totalCustomers = class_exists(Customer::class) ? Customer::count() : 0;
        $totalInvoices = class_exists(Invoice::class) ? Invoice::count() : 0;
        $totalEstimates = class_exists(Estimate::class) ? Estimate::count() : 0;

        
        $totalSales = class_exists(Invoice::class) ? Invoice::sum('total_amount') : 0;
        $totalReceipts = class_exists(Payment::class) ? Payment::sum('amount') : 0;
        $totalExpenses = class_exists(Expense::class) ? Expense::sum('amount') : 0;
        $netIncome = $totalSales - $totalExpenses;

        
        $dueInvoices = class_exists(Invoice::class) 
            ? Invoice::with('customer')
                ->when(Schema::hasColumn('invoices', 'status'), function ($q) {
                    return $q->where('status', '!=', 'PAID');
                })
                ->latest()
                ->take(5)
                ->get()
            : collect();

       
        $recentEstimates = class_exists(Estimate::class) 
            ? Estimate::with('customer')->latest()->take(5)->get() 
            : collect();

        
        $months = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $salesData = [];
        $receiptsData = [];
        $expensesData = [];

        $currentYear = Carbon::now()->year;

        foreach ($months as $index => $month) {
            
            $monthNum = ($index + 7) > 12 ? ($index - 5) : ($index + 7);
            $year = ($index + 7) > 12 ? $currentYear + 1 : $currentYear;

            
            $salesData[] = class_exists(Invoice::class) && Schema::hasColumn('invoices', 'invoice_date')
                ? Invoice::whereYear('invoice_date', $year)->whereMonth('invoice_date', $monthNum)->sum('total_amount')
                : 0;

            $receiptsData[] = class_exists(Payment::class) && Schema::hasColumn('payments', 'payment_date')
                ? Payment::whereYear('payment_date', $year)->whereMonth('payment_date', $monthNum)->sum('amount')
                : 0;

            $expensesData[] = class_exists(Expense::class) && Schema::hasColumn('expenses', 'expense_date')
                ? Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $monthNum)->sum('amount')
                : 0;
        }

        return view('dashboard', compact(
            'totalAmountDue',
            'totalCustomers',
            'totalInvoices',
            'totalEstimates',
            'totalSales',
            'totalReceipts',
            'totalExpenses',
            'netIncome',
            'dueInvoices',
            'recentEstimates',
            'months',
            'salesData',
            'receiptsData',
            'expensesData'
        ));
    }
}