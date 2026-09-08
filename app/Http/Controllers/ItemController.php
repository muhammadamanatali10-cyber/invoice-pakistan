<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUnit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        
        $units = ItemUnit::all();

        $query = Item::latest();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($request->filled('price')) {
            $query->where('price', $request->price);
        }

        $items = $query->paginate(10)->withQueryString();

        
        return view('items.index', compact('items', 'units'));
    }

    public function create()
    {
        $units = ItemUnit::all();
        return view('items.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $units = ItemUnit::all();
        return view('items.edit', compact('item', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:item_units,name',
        ]);

        $unit = ItemUnit::create(['name' => $request->name]);

        return response()->json(['success' => true, 'unit' => $unit]);
    }
}