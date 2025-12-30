<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();
        if ($request->filled("search")) {
            $query->where("item_name", "like", "%{$request->search}%")
                ->orWhere("barcode", "like", "%{$request->search}%");
        }
        if ($request->filled("unit")) {
            $query->where("unit", $request->unit);
        }

        $items = $query->orderBy("id", "desc")->paginate(50);
        $units = Item::select("unit")->distinct()->whereNotNull("unit")->pluck("unit");

        return view("master.items", compact("items", "units"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "item_name" => "required|string|max:255",
            "unit" => "required|string|max:50",
            "barcode" => "nullable|unique:items,barcode"
        ]);

        Item::create($request->all());
        return redirect()->route("items.index")->with("success", "تم إضافة الصنف بنجاح");
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            "item_name" => "required|string|max:255",
            "unit" => "required|string|max:50",
            "barcode" => ["nullable", Rule::unique("items")->ignore($item->id)]
        ]);

        $item->update($request->all());
        return redirect()->route("items.index")->with("success", "تم تحديث الصنف بنجاح");
    }

    public function destroy(Item $item)
    {
        try {
            $item->delete();
            return redirect()->route("items.index")->with("success", "تم حذف الصنف");
        } catch (QueryException $e) {
            return redirect()->route("items.index")->with("error", "لا يمكن حذف الصنف لأنه مسجل في عمليات جرد أو عهد.");
        }
    }
}
