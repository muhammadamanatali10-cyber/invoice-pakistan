<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PayableController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $query = Payable::with('supplier')->latest();

        
        $tab = $request->get('tab', 'unpaid'); 
        if ($tab === 'unpaid') {
            $query->where('paid_status', 'UNPAID');
        } elseif ($tab === 'paid') {
            $query->where('paid_status', 'PAID');
        }

        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('status')) {
            $query->where('paid_status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'LIKE', '%' . $request->invoice_number . '%');
        }

        $payables = $query->paginate(10)->appends($request->all());

        return view('payables.index', compact('payables', 'suppliers', 'tab'));
    }
}