<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all(); // The data for the "Excel Sheet"
        return view('master.items', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'barcode'   => 'nullable|string|unique:items,barcode',
            'unit'      => 'required|string',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Row added successfully');
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'barcode'   => 'nullable|string|unique:items,barcode,' . $item->id,
            'unit'      => 'required|string',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Row updated successfully');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Row deleted.');
    }
}
