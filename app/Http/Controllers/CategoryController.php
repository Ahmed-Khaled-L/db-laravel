<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $categories = $query->orderBy('id')->get();
        $types = Category::select('type')->distinct()->pluck('type');

        return view('master.categories', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|max:50',
            'cat_name' => 'required|string|max:255',
            'organization' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $exists = Category::where('id', $request->id)->where('type', $request->type)->exists();
        if ($exists) {
            return back()->with('error', 'هذا البند (الرقم + النوع) موجود بالفعل.');
        }

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'تم إضافة البند بنجاح');
    }

    public function update(Request $request, $id, $type)
    {
        $type = urldecode($type);
        $category = Category::where('id', $id)->where('type', $type)->firstOrFail();

        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|max:50',
            'cat_name' => 'required|string|max:255',
        ]);

        // Handle Composite Key Change
        if ($request->id != $id || $request->type != $type) {
            $exists = Category::where('id', $request->id)->where('type', $request->type)->exists();
            if ($exists) {
                return back()->with('error', 'لا يمكن التعديل: البند الجديد موجود بالفعل.');
            }
            // Create new, delete old (since PK changed)
            try {
                Category::create($request->all());
                $category->delete();
            } catch (QueryException $e) {
                return back()->with('error', 'حدث خطأ أثناء تحديث المفاتيح.');
            }
        } else {
            $category->update($request->only(['cat_name', 'organization', 'notes']));
        }

        return redirect()->route('categories.index')->with('success', 'تم تحديث البند بنجاح');
    }

    public function destroy($id, $type)
    {
        $type = urldecode($type);
        try {
            Category::where('id', $id)->where('type', $type)->delete();
            return redirect()->route('categories.index')->with('success', 'تم حذف البند بنجاح');
        } catch (QueryException $e) {
            return redirect()->route('categories.index')->with('error', 'لا يمكن حذف هذا البند لارتباطه ببيانات أخرى (مثل المخازن أو الأصناف).');
        }
    }
}
