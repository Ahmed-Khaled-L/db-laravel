<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('master.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer', // User must define ID manually or you auto-gen it
            'type' => 'required|string',
            'cat_name' => 'required|string',
            'organization' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Prevent duplicate composite key
        $exists = Category::where('id', $request->id)
            ->where('type', $request->type)->exists();

        if ($exists) {
            return back()->withErrors(['id' => 'This ID+Type combination already exists.']);
        }

        Category::create($request->all());
        return redirect()->route('categories.index');
    }

    // We pass ID and Type via query string or hidden input for updates
    public function update(Request $request)
    {
        $category = Category::where('id', $request->original_id)
            ->where('type', $request->original_type)
            ->firstOrFail();

        $category->update([
            'id' => 'required|integer', // User must define ID manually or you auto-gen it
            'type' => 'required|string',
            'cat_name' => $request->cat_name,
            'organization' => $request->organization,
            'notes' => $request->notes
            // Note: We usually don't allow changing PKs (ID/Type)
        ]);

        return redirect()->route('categories.index');
    }

    public function destroy(Request $request)
    {

        // dd($request->all());
        Category::where('id', $request->id)
            ->where('type', $request->type)
            ->delete();

        return redirect()->route('categories.index');
    }
}
