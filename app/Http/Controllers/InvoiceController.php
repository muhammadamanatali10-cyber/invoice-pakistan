<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Estimate;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $customers = Customer::all();
        $items = Item::all();
        $estimate = null;

        
        $estimateId = $request->get('from_estimate') ?? $request->get('estimate_id');

        if ($estimateId) {
            $estimate = Estimate::with('items', 'customer')->find($estimateId);
        } 
        
        elseif (session()->has('estimate_data')) {
            $estimateData = session('estimate_data');
            $estId = is_array($estimateData) ? ($estimateData['id'] ?? null) : ($estimateData->id ?? null);
            if ($estId) {
                $estimate = Estimate::with('items', 'customer')->find($estId);
            }
        }

        
        $latestInvoice = Invoice::latest()->first();
        $nextInvoiceNumber = 'INV - ' . str_pad($latestInvoice ? $latestInvoice->id + 1 : 1, 6, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('customers', 'items', 'estimate', 'nextInvoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $subTotal = 0;
            foreach ($request->items as $item) {
                $subTotal += ($item['qty'] ?? 1) * ($item['price'] ?? 0);
            }

            $discount = $request->discount ?? 0;
            $discountType = $request->discount_type ?? 'flat';
            $discountAmount = ($discountType === 'percent') ? ($subTotal * $discount) / 100 : $discount;
            $totalAmount = max(0, $subTotal - $discountAmount);

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'estimate_id' => $request->estimate_id,
                'invoice_number' => $request->invoice_number ?? 'INV - ' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT),
                'ref_number' => $request->ref_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'discount_type' => $discountType,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'unpaid',
            ]);

            foreach ($request->items as $item) {
                if (!empty($item['name']) || !empty($item['item_id'])) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_id' => $item['item_id'] ?? null,
                        'item_name' => $item['name'] ?? $item['item_name'] ?? null,
                        'description' => $item['description'] ?? null,
                        'quantity' => $item['qty'] ?? 1,
                        'price' => $item['price'] ?? 0,
                        'amount' => ($item['qty'] ?? 1) * ($item['price'] ?? 0),
                    ]);
                }
            }

            
            if ($request->estimate_id) {
                Estimate::where('id', $request->estimate_id)->update(['status' => 'ACCEPTED']);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

   
    
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'Paid' 
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice has been marked as Paid successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}