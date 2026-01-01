<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Employee;
use App\Models\Item;       // Import Item
use App\Models\Category;   // Import Category
use App\Models\StoreItemMapping; // Import Mapping
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        // Eager load mappings and items to show them in the view
        $query = Store::with(["manager", "items"]);
        if ($request->filled("responsible_employee_id")) {
            $query->where("responsible_employee_id", $request->responsible_employee_id);
        }
        $stores = $query->get();
        $employees = Employee::orderBy("name")->get();

        // Pass Items and Categories if you want to add them in a modal on the index page
        $allItems = Item::all();
        $allCategories = Category::all();

        return view("master.stores", compact("stores", "employees", "allItems", "allCategories"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|unique:stores,name|max:100",
            "code" => "nullable|unique:stores,code",
            "responsible_employee_id" => "nullable|exists:employees,id",
            "classification" => "required|string",
            "custody_type" => "required|string",
        ]);

        Store::create($request->all());
        return redirect()->route("stores.index")->with("success", "تم إضافة المخزن بنجاح.");
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            "name" => ["required", "string", "max:100", Rule::unique("stores", "name")->ignore($store->id)],
            "code" => ["nullable", Rule::unique("stores", "code")->ignore($store->id)],
            "responsible_employee_id" => "nullable|exists:employees,id",
            "classification" => "required|string",
            "custody_type" => "required|string",
        ]);

        $store->update($request->all());
        return redirect()->route("stores.index")->with("success", "تم تحديث البيانات بنجاح.");
    }

    public function destroy(Store $store)
    {
        try {
            // Detach items first to avoid foreign key constraints if cascade isn't set
            $store->items()->detach();
            $store->delete();
            return redirect()->route("stores.index")->with("success", "تم حذف المخزن.");
        } catch (QueryException $e) {
            return redirect()->route("stores.index")->with("error", "حدث خطأ أثناء الحذف: " . $e->getMessage());
        }
    }

    // --- NEW METHOD: Assign Item to Store ---
    public function assignItem(Request $request, $storeId)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Use firstOrCreate to prevent duplicates
        StoreItemMapping::firstOrCreate(
            [
                'store_id' => $storeId,
                'item_id' => $request->item_id
            ],
            [
                'category_id' => $request->category_id
            ]
        );

        return back()->with('success', 'تم تخصيص الصنف للمخزن بنجاح');
    }

    // --- NEW METHOD: Remove Item from Store ---
    public function removeItem($storeId, $itemId)
    {
        StoreItemMapping::where('store_id', $storeId)
            ->where('item_id', $itemId)
            ->delete();

        return back()->with('success', 'تم إزالة الصنف من المخزن');
    }
}
