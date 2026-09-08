<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::all();
        $query = Payment::with(['customer', 'invoice'])->latest();

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('payment_number')) {
            $query->where('payment_number', 'LIKE', '%' . $request->payment_number . '%');
        }
        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }

        $payments = $query->paginate(10);

        return view('payments.index', compact('payments', 'customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        $invoices = Invoice::with('customer')->get();
        
        $nextPaymentNum = 'PAY - ' . str_pad(Payment::count() + 1, 6, '0', STR_PAD_LEFT);

        return view('payments.create', compact('customers', 'invoices', 'nextPaymentNum'));
    }

    public function getCustomerInvoices($customerId)
    {
        $invoices = Invoice::where('customer_id', $customerId)->get();

        $data = $invoices->map(function ($invoice) {
            return [
                'id'             => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'customer_id'    => $invoice->customer_id,
                'total_amount'   => (float) $invoice->total_amount,
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_date'   => 'required|date',
            'customer_id'    => 'required|exists:customers,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_mode'   => 'nullable|string',
            'invoice_id'     => 'nullable|exists:invoices,id',
            'payment_number' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                Payment::create([
                    'payment_date'   => $request->payment_date,
                    'customer_id'    => $request->customer_id,
                    'invoice_id'     => $request->invoice_id,
                    'payment_number' => $request->payment_number,
                    'payment_mode'   => $request->payment_mode,
                    'amount'         => $request->amount,
                    'notes'          => $request->notes,
                ]);

                
                if ($request->filled('invoice_id')) {
                    $invoice = Invoice::find($request->invoice_id);
                    if ($invoice) {
                        $invoice->update([
                            'status' => 'paid'
                        ]);
                    }
                }
            });

            return redirect()->route('payments.index')->with('success', 'Payment recorded and invoice marked as paid successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Database Exception: ' . $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}