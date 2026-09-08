<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Expense;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'sales');
        $dateRange = $request->get('date_range', 'this_month');
        $reportType = $request->get('report_type', 'by_customer');

        
        [$fromDate, $toDate] = $this->getDateRange($dateRange, $request);

        
        $data = $this->getReportData($tab, $fromDate, $toDate, $reportType);

        return view('reports.index', compact('tab', 'dateRange', 'reportType', 'fromDate', 'toDate', 'data'));
    }

    public function downloadPdf(Request $request)
    {
        $tab = $request->get('tab', 'sales');
        $dateRange = $request->get('date_range', 'this_month');
        $reportType = $request->get('report_type', 'by_customer');

        [$fromDate, $toDate] = $this->getDateRange($dateRange, $request);
        $data = $this->getReportData($tab, $fromDate, $toDate, $reportType);

        $pdf = Pdf::loadView('reports.pdf_template', compact('tab', 'fromDate', 'toDate', 'data'));

        
        if ($request->has('preview')) {
            return $pdf->stream("{$tab}_report.pdf");
        }

        
        return $pdf->download("{$tab}_report.pdf");
    }

    private function getDateRange($dateRange, Request $request)
    {
        if ($dateRange === 'custom') {
            return [$request->from_date, $request->to_date];
        }

        return [
            Carbon::now()->startOfMonth()->toDateString(),
            Carbon::now()->endOfMonth()->toDateString()
        ];
    }

    private function getReportData($tab, $fromDate, $toDate, $reportType)
    {
        if ($tab === 'sales') {
            return Invoice::with('customer')
                ->whereBetween('invoice_date', [$fromDate, $toDate])
                ->get()
                ->groupBy('customer_id');
        }

        if ($tab === 'profit_loss') {
            $income = Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->sum('total_amount');
            $expenses = Expense::whereBetween('expense_date', [$fromDate, $toDate])->sum('amount');
            return [
                'income' => $income,
                'expenses' => $expenses,
                'net_profit' => $income - $expenses
            ];
        }

        if ($tab === 'expenses') {
            return Expense::with('category')
                ->whereBetween('expense_date', [$fromDate, $toDate])
                ->get()
                ->groupBy('expense_category_id');
        }

        if ($tab === 'taxes') {
    
    $totalTax = 0; 
    return ['total_tax' => $totalTax];
}

        return [];
    }
}