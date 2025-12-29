<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        // 1. Search Filter (Name or Barcode)
        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("item_name", "like", "%{$search}%")->orWhere(
                    "barcode",
                    "like",
                    "%{$search}%",
                );
            });
        }

        // 2. Unit Filter
        if ($request->filled("unit")) {
            $query->where("unit", $request->unit);
        }

        $items = $query->orderBy("id", "desc")->paginate(50);

        // --- FIX: Fetch unique units for the dropdown ---
        // This variable ($units) was missing in your controller, causing the error.
        $units = Item::select("unit")
            ->distinct()
            ->whereNotNull("unit")
            ->pluck("unit");

        return view("master.items", compact("items", "units"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "item_name" => "required|string|max:255",
                "barcode" => "nullable|string|unique:items,barcode|max:100",
                "unit" => "required|string|max:50",
                "description" => "nullable|string",
            ],
            [
                "item_name.required" => "اسم الصنف مطلوب",
                "barcode.unique" => "هذا الباركود مسجل مسبقاً",
                "unit.required" => "يرجى تحديد الوحدة",
            ],
        );

        Item::create($request->all());

        return redirect()
            ->route("items.index")
            ->with("success", "تم إضافة الصنف بنجاح");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            "item_name" => "required|string|max:255",
            "barcode" => [
                "nullable",
                "string",
                Rule::unique("items")->ignore($item->id),
            ],
            "unit" => "required|string|max:50",
            "description" => "nullable|string",
        ]);

        $item->update($request->all());

        return redirect()
            ->route("items.index")
            ->with("success", "تم تحديث الصنف بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()
            ->route("items.index")
            ->with("success", "تم حذف الصنف");
    }
}
