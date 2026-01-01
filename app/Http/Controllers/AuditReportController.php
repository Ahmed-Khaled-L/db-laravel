<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Models\Category;
use App\Models\Employee;

class AuditReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Determine Audit Type (main, branch, personnel)
        $auditType = $request->input('type', 'main');

        // 2. Fetch Filter Options
        $stores = [];
        $employees = [];
        // Distinct categories to avoid duplicates in dropdown
        $categories = Category::select('id', 'cat_name', 'type')->distinct('id')->orderBy('id')->get();

        if ($auditType === 'personnel') {
            $employees = Employee::select('id', 'name')->orderBy('name')->get();
        } else {
            $storeCustodyType = ($auditType === 'main') ? 'مخزن رئيسي' : 'عهدة فرعية';
            $stores = Store::where('custody_type', $storeCustodyType)->get();
        }
        // 3. Handle Query Submission
        $records = [];
        $selectedStore = null;
        $selectedEmployee = null;
        $selectedCategory = null;

        if ($request->has('submit_filter')) {
            $categoryId = $request->input('category_id');
            // Fetch object if specific ID selected, otherwise null implies "All"
            if ($categoryId) {
                $selectedCategory = Category::where('id', $categoryId)->first();
            }

            if ($auditType === 'personnel') {
                $employeeId = $request->input('employee_id');
                if ($employeeId) {
                    $selectedEmployee = Employee::find($employeeId);
                }

                $query = DB::table('personnel_custody_audits')
                    ->join('custody_audit_bases', 'personnel_custody_audits.id', '=', 'custody_audit_bases.id')
                    ->join('items', 'custody_audit_bases.item_id', '=', 'items.id');

                // Apply Filters Conditionally
                if ($employeeId) {
                    $query->where('personnel_custody_audits.employee_id', $employeeId);
                }
                if ($categoryId) {
                    $query->where('personnel_custody_audits.category_id', $categoryId);
                }

                $records = $query->select(
                    'items.id as item_number',
                    'items.item_name',
                    'items.unit',
                    'custody_audit_bases.unit_price',
                    'personnel_custody_audits.quantity as actual_qty',
                    'personnel_custody_audits.quantity as book_qty',
                    DB::raw("'جديد' as item_state")
                )
                    ->get();
            } else {
                // Main or Branch Store Audit
                $storeId = $request->input('store_id');
                if ($storeId) {
                    $selectedStore = Store::find($storeId);
                }

                $query = DB::table('inventory_audits')
                    ->join('custody_audit_bases', 'inventory_audits.id', '=', 'custody_audit_bases.id')
                    ->join('items', 'custody_audit_bases.item_id', '=', 'items.id')
                    ->join('store_item_mappings', function ($join) {
                        $join->on('inventory_audits.store_id', '=', 'store_item_mappings.store_id')
                            ->on('custody_audit_bases.item_id', '=', 'store_item_mappings.item_id');
                    });

                // Ensure we only look at stores of the correct type (Main vs Branch)
                // even if "All Stores" is selected.
                $storeCustodyType = ($auditType === 'main') ? 'مخزن رئيسي' : 'عهدة فرعية';
                $query->join('stores', 'inventory_audits.store_id', '=', 'stores.id')
                    ->where('stores.custody_type', $storeCustodyType);

                // Apply Filters Conditionally
                if ($storeId) {
                    $query->where('inventory_audits.store_id', $storeId);
                }
                if ($categoryId) {
                    $query->where('store_item_mappings.category_id', $categoryId);
                }

                $records = $query->select(
                    'items.id as item_number',
                    'items.item_name',
                    'items.unit',
                    'custody_audit_bases.unit_price',
                    'inventory_audits.observed_quantity as actual_qty',
                    'inventory_audits.booked_quantity as book_qty',
                    'inventory_audits.observed_state as item_state'
                )
                    ->get();
            }
        }
        return view('reports.audit.index', compact(
            'auditType',
            'stores',
            'employees',
            'categories',
            'records',
            'selectedStore',
            'selectedEmployee',
            'selectedCategory'
        ));
    }
}
