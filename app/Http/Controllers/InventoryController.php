<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Store;

class InventoryController extends Controller
{
    /**
     * Display the Inventory Audit Report.
     */
    public function index(Request $request)
    {
        // 1. Build the Query
        $query = DB::table("inventory_audits")
            ->join(
                "custody_audit_bases",
                "inventory_audits.id",
                "=",
                "custody_audit_bases.id",
            )
            ->join("items", "custody_audit_bases.item_id", "=", "items.id")
            ->join("stores", "inventory_audits.store_id", "=", "stores.id")
            ->join(
                "employees",
                "stores.responsible_employee_id",
                "=",
                "employees.id",
            )
            ->leftJoin(
                "registers",
                "custody_audit_bases.register_id",
                "=",
                "registers.id",
            )
            // Left Join for Category Mapping (in case it's missing)
            ->leftJoin("store_item_mappings", function ($join) {
                $join
                    ->on("stores.id", "=", "store_item_mappings.store_id")
                    ->on("items.id", "=", "store_item_mappings.item_id");
            })
            ->select(
                "items.item_name",
                "items.unit",
                "inventory_audits.observed_quantity",
                "inventory_audits.booked_quantity",
                "inventory_audits.observed_state",
                "custody_audit_bases.unit_price",
                "custody_audit_bases.page_no",
                "registers.register_name",
                "stores.name as store_name",
                "stores.custody_type",
                "employees.name as manager_name",
                "store_item_mappings.category_id", // Might be null
                // Calculations
                DB::raw(
                    "GREATEST(inventory_audits.observed_quantity - inventory_audits.booked_quantity, 0) as excess",
                ),
                DB::raw(
                    "GREATEST(inventory_audits.booked_quantity - inventory_audits.observed_quantity, 0) as deficit",
                ),
                DB::raw(
                    "(inventory_audits.observed_quantity * custody_audit_bases.unit_price) as total_value",
                ),
            );

        // 2. Apply Filters
        if ($request->has("store_id") && $request->store_id != "") {
            $query->where("inventory_audits.store_id", $request->store_id);
        }

        // 3. Execute
        $rows = $query->get();

        // 4. Get options for Filter Dropdown
        $stores = Store::select("id", "name")->orderBy("name")->get();

        return view("inventory.report", compact("rows", "stores"));
    }
}
