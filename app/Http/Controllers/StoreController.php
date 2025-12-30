<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with("manager");
        if ($request->filled("responsible_employee_id")) {
            $query->where("responsible_employee_id", $request->responsible_employee_id);
        }
        $stores = $query->get();
        $employees = Employee::orderBy("name")->get();

        return view("master.stores", compact("stores", "employees"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|unique:stores,name|max:100",
            "code" => "nullable|unique:stores,code", // removed 'integer' to allow alphanumeric codes if needed
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
            $store->delete();
            return redirect()->route("stores.index")->with("success", "تم حذف المخزن.");
        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route("stores.index")->with("error", "لا يمكن حذف المخزن لأنه يحتوي على أصناف أو سجلات جرد.");
            }
            return redirect()->route("stores.index")->with("error", "حدث خطأ أثناء الحذف.");
        }
    }
}
