<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $categories = ExpenseCategory::all();
        $query = Expense::with('category')->latest();

        
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $expenses = $query->paginate(10);

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'category_name' => 'required|string|max:255',
            'expense_date'  => 'required|date',
            'amount'        => 'required|numeric|min:0.01',
            'receipt'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        
        $category = ExpenseCategory::firstOrCreate([
            'name' => trim($request->category_name)
        ]);

       
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

       
        Expense::create([
            'expense_category_id' => $category->id,
            'expense_date'        => $request->expense_date,
            'amount'              => $request->amount,
            'notes'               => $request->notes,
            'receipt'             => $receiptPath,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt && Storage::disk('public')->exists($expense->receipt)) {
            Storage::disk('public')->delete($expense->receipt);
        }
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}