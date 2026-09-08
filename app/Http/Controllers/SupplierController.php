<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::latest();

        if ($request->filled('search')) {
            $query->where('display_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        $suppliers = $query->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.invite');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:suppliers,email',
        ]);

        
        $nameFromEmail = explode('@', $request->email)[0];
        $formattedName = ucwords(str_replace('.', ' ', $nameFromEmail));

        Supplier::create([
            'email'        => $request->email,
            'display_name' => $formattedName,
            'status'       => 'INVITED',
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Invitation sent successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}