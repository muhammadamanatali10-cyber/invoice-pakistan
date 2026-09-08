<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstimateController extends Controller
{
    public function index()
    {
        $estimates = Estimate::with('customer')->latest()->paginate(10);
        return view('estimates.index', compact('estimates'));
    }

    public function create()
    {
        $customers = Customer::all();
        $items = Item::all();

        $nextEstimateNumber = 'EST - ' . str_pad((Estimate::max('id') + 1), 6, '0', STR_PAD_LEFT);

        return view('estimates.create', compact('customers', 'items', 'nextEstimateNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'estimate_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $subTotal = 0;
            foreach ($request->items as $item) {
                $qty = $item['qty'] ?? 1;
                $price = $item['price'] ?? 0;
                $subTotal += $qty * $price;
            }

            $discount = $request->discount_value ?? 0;
            $discountType = $request->discount_type ?? 'flat';
            $discountAmount = ($discountType === 'percent') ? ($subTotal * $discount) / 100 : $discount;
            $totalAmount = max(0, $subTotal - $discountAmount);

            $estimate = Estimate::create([
                'customer_id' => $request->customer_id,
                'estimate_number' => $request->estimate_number ?? 'EST-' . time(),
                'ref_number' => $request->ref_number,
                'estimate_date' => $request->estimate_date,
                'due_date' => $request->due_date,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'discount_type' => $discountType,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'DRAFT',
            ]);

            foreach ($request->items as $itemData) {
                $qty = $itemData['qty'] ?? 1;
                $price = $itemData['price'] ?? 0;
                $itemId = $itemData['item_id'] ?? null;
                
                $itemName = $itemData['item_name'] ?? null;
                if (!$itemName && $itemId) {
                    $item = Item::find($itemId);
                    $itemName = $item ? $item->name : null;
                }

                EstimateItem::create([
                    'estimate_id' => $estimate->id,
                    'item_id' => $itemId,
                    'item_name' => $itemName,
                    'description' => $itemData['description'] ?? null,
                    'quantity' => $qty,
                    'price' => $price,
                    'amount' => $qty * $price,
                ]);
            }
        });

        return redirect()->route('estimates.index')->with('success', 'Estimate created successfully.');
    }

    public function convertToInvoice($id)
    {
        $estimate = Estimate::with('items')->findOrFail($id);

        $convertedData = [
            'customer_id'   => $estimate->customer_id,
            'estimate_id'   => $estimate->id,
            'sub_total'     => $estimate->sub_total ?? $estimate->total_amount,
            'discount'      => $estimate->discount ?? 0,
            'discount_type' => $estimate->discount_type ?? 'flat',
            'total_amount'  => $estimate->total_amount,
            'notes'         => $estimate->notes,
            'items'         => $estimate->items->map(function ($item) {
                return [
                    'item_id'     => $item->item_id,
                    'item_name'   => $item->item_name,
                    'description' => $item->description,
                    'quantity'    => $item->quantity,
                    'price'       => $item->price,
                    'amount'      => $item->amount,
                ];
            })->toArray(),
        ];

        return redirect()->route('invoices.create')->with('estimate_data', $convertedData);
    }

    public function destroy(Estimate $estimate)
    {
        $estimate->delete();
        return redirect()->route('estimates.index')->with('success', 'Estimate deleted successfully.');
    }
}