<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Display the CRUD SPA View
    public function index()
    {
        // Get all assets ordered by ID (Serial Number)
        $assets = Asset::orderBy('id')->get();
        return view('assets.index', compact('assets'));
    }

    // Store a new Asset
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|unique:assets,id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|numeric',
        ]);

        Asset::create([
            'id' => $request->id,
            'name' => $request->name,
            'value' => $request->value ?? 0,
        ]);

        return redirect()->route('assets.index')->with('success', 'تم إضافة الأصل بنجاح');
    }

    // Update an existing Asset
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'id' => 'required|integer|unique:assets,id,' . $asset->id,
            'name' => 'required|string|max:255',
            'value' => 'nullable|numeric',
        ]);

        $asset->update([
            'id' => $request->id,
            'name' => $request->name,
            'value' => $request->value ?? 0,
        ]);

        return redirect()->route('assets.index')->with('success', 'تم تحديث الأصل بنجاح');
    }

    // Delete an Asset
    public function destroy($id)
    {
        Asset::destroy($id);
        return redirect()->route('assets.index')->with('success', 'تم حذف الأصل بنجاح');
    }

    // Display the Printable Report View
    public function report()
    {
        $assets = Asset::orderBy('id')->get();
        $totalValue = $assets->sum('value');

        return view('reports.assets', compact('assets', 'totalValue'));
    }
}
