<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the Custody Audit Report.
     */
    public function custodyReport()
    {
        // 1. Get all distinct category types present in the system
        $types = Category::select('type')->distinct()->pluck('type');

        $reportData = [];

        foreach ($types as $type) {
            // Get all categories for this type, ordered by ID
            $categories = Category::where('type', $type)->orderBy('id')->get();

            $sectionData = [];

            foreach ($categories as $category) {
                // --- A. Calculate Personnel Custody Value ---
                // Query: PersonnelCustodyAudit -> Join Base (for unit_price)
                $personnelValue = DB::table('personnel_custody_audits')
                    ->join('custody_audit_bases', 'personnel_custody_audits.id', '=', 'custody_audit_bases.id')
                    ->where('personnel_custody_audits.category_id', $category->id)
                    ->where('personnel_custody_audits.category_type', $category->type)
                    ->sum(DB::raw('personnel_custody_audits.quantity * custody_audit_bases.unit_price'));

                // --- B. Calculate Inventory Custody Value (Main vs Branch) ---
                // Query: InventoryAudit -> Join Base (for item) -> Join Store (for type) -> Join Mapping (for category)
                $inventoryQuery = DB::table('inventory_audits')
                    ->join('custody_audit_bases', 'inventory_audits.id', '=', 'custody_audit_bases.id')
                    ->join('stores', 'inventory_audits.store_id', '=', 'stores.id')
                    ->join('store_item_mappings', function ($join) {
                        $join->on('inventory_audits.store_id', '=', 'store_item_mappings.store_id')
                            ->on('custody_audit_bases.item_id', '=', 'store_item_mappings.item_id');
                    })
                    // Filter by Category ID
                    ->where('store_item_mappings.category_id', $category->id)
                    // Filter by Store Classification matching Category Type (Composite Key Logic)
                    ->where('stores.classification', $category->type)
                    ->select(
                        'stores.custody_type',
                        DB::raw('SUM(inventory_audits.booked_quantity * custody_audit_bases.unit_price) as total_value')
                    )
                    ->groupBy('stores.custody_type')
                    ->get();

                // Extract values based on custody_type string from Seeder
                $mainWhValue = $inventoryQuery->where('custody_type', 'مخزن رئيسي')->first()->total_value ?? 0;
                $branchWhValue = $inventoryQuery->where('custody_type', 'عهدة فرعية')->first()->total_value ?? 0;

                // Prepare Row Data
                $sectionData[] = [
                    'id' => $category->id,
                    'name' => $category->cat_name,
                    'personnel_value' => $personnelValue,
                    'main_wh_value' => $mainWhValue,
                    'branch_wh_value' => $branchWhValue,
                    'total' => $personnelValue + $mainWhValue + $branchWhValue
                ];
            }

            $reportData[$type] = $sectionData;
        }

        return view('reports.custody', compact('reportData'));
    }
}
