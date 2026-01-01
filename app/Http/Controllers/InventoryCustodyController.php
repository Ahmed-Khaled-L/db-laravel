<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustodyAuditBase;
use App\Models\InventoryAudit;
use App\Models\Register;
use App\Models\RegisterPage;
use App\Models\Item;
use App\Models\Store;
use App\Models\ItemDetails;
use App\Models\Category;           // Imported
use App\Models\StoreItemMapping;   // Imported

class InventoryCustodyController extends Controller
{
    public function index()
    {
        $audits = CustodyAuditBase::where('audit_type', 'Inventory')
            ->with(['inventoryDetail.store', 'item', 'register', 'itemDetails'])
            ->latest()
            ->get();

        return view('custody.inventory.index', compact('audits'));
    }

    public function create()
    {
        $registers = Register::all();
        $items = Item::all();
        $stores = Store::all();

        // NEW: Fetch Categories for the dropdowns
        $categories = Category::all();
        $types = $categories->pluck('type')->unique();

        return view('custody.inventory.create', compact('registers', 'items', 'stores', 'categories', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'register_id' => 'required|exists:registers,id',
            'page_no' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'unit_price' => 'required|numeric|min:0',
            'store_id' => 'required|exists:stores,id',

            // NEW: Category Validation
            'category_id' => 'required|exists:categories,id',
            'category_type' => 'required', // Optional validation for UX consistency

            'observed_quantity' => 'required|integer|min:0',
            'booked_quantity' => 'required|integer|min:0',
            'observed_state' => 'required|string',
            'booked_state' => 'required|string',
            'notes' => 'nullable|string',
            'add_details' => 'nullable|boolean',
        ]);

        $auditId = DB::transaction(function () use ($validated) {

            // 1. Ensure Register Page Exists
            RegisterPage::firstOrCreate(
                [
                    'register_id' => $validated['register_id'],
                    'page_number' => $validated['page_no']
                ],
                [
                    'store_id' => $validated['store_id']
                ]
            );

            // 2. Create Base Record
            $base = CustodyAuditBase::create([
                'date' => now(),
                'unit_price' => $validated['unit_price'],
                'register_id' => $validated['register_id'],
                'page_no' => $validated['page_no'],
                'item_id' => $validated['item_id'],
                'audit_type' => 'Inventory',
                'notes' => $validated['notes'],
            ]);

            // 3. Create Inventory Specific Record
            InventoryAudit::create([
                'id' => $base->id,
                'store_id' => $validated['store_id'],
                'observed_quantity' => $validated['observed_quantity'],
                'booked_quantity' => $validated['booked_quantity'],
                'observed_state' => $validated['observed_state'],
                'booked_state' => $validated['booked_state'],
            ]);

            // 4. NEW: Create or Update the Store-Item Mapping
            // This ensures we capture the Category ID for this Item in this Store
            StoreItemMapping::updateOrCreate(
                [
                    'store_id' => $validated['store_id'],
                    'item_id' => $validated['item_id']
                ],
                [
                    'category_id' => $validated['category_id']
                ]
            );

            return $base->id;
        });

        if ($request->has('add_details') && $request->add_details == 1) {
            return redirect()->route('custody.inventory.details', ['auditId' => $auditId]);
        }

        return redirect()->route('custody.inventory.index')->with('success', 'تم إضافة عهدة المخزن وتحديث تعيين الصنف بنجاح');
    }

    public function edit($id)
    {
        $audit = CustodyAuditBase::with(['inventoryDetail', 'register', 'item'])->findOrFail($id);

        if ($audit->audit_type !== 'Inventory') {
            return redirect()->route('custody.inventory.index')->with('error', 'هذا السجل ليس عهدة مخزنية');
        }

        $registers = Register::all();
        $items = Item::all();
        $stores = Store::all();

        // NEW: Fetch Categories for Edit
        $categories = Category::all();
        $types = $categories->pluck('type')->unique();

        // Try to find existing mapping to pre-fill category
        $currentMapping = StoreItemMapping::where('store_id', $audit->inventoryDetail->store_id)
            ->where('item_id', $audit->item_id)
            ->first();

        $currentCategoryId = $currentMapping ? $currentMapping->category_id : null;
        $currentCategoryType = $currentMapping && $currentMapping->category ? $currentMapping->category->type : null;

        return view('custody.inventory.edit', compact(
            'audit',
            'registers',
            'items',
            'stores',
            'categories',
            'types',
            'currentCategoryId',
            'currentCategoryType'
        ));
    }

    public function update(Request $request, $id)
    {
        $audit = CustodyAuditBase::findOrFail($id);

        $validated = $request->validate([
            'register_id' => 'required|exists:registers,id',
            'page_no' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'unit_price' => 'required|numeric|min:0',
            'store_id' => 'required|exists:stores,id',

            // NEW: Category Validation
            'category_id' => 'required|exists:categories,id',

            'observed_quantity' => 'required|integer|min:0',
            'booked_quantity' => 'required|integer|min:0',
            'observed_state' => 'required|string',
            'booked_state' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $audit) {
            // Update Page
            RegisterPage::firstOrCreate(
                [
                    'register_id' => $validated['register_id'],
                    'page_number' => $validated['page_no']
                ],
                [
                    'store_id' => $validated['store_id']
                ]
            );

            // Update Base
            $audit->update([
                'unit_price' => $validated['unit_price'],
                'register_id' => $validated['register_id'],
                'page_no' => $validated['page_no'],
                'item_id' => $validated['item_id'],
                'notes' => $validated['notes'],
            ]);

            // Update Inventory Detail
            $audit->inventoryDetail()->update([
                'store_id' => $validated['store_id'],
                'observed_quantity' => $validated['observed_quantity'],
                'booked_quantity' => $validated['booked_quantity'],
                'observed_state' => $validated['observed_state'],
                'booked_state' => $validated['booked_state'],
            ]);

            // NEW: Update Store-Item Mapping
            StoreItemMapping::updateOrCreate(
                [
                    'store_id' => $validated['store_id'],
                    'item_id' => $validated['item_id']
                ],
                [
                    'category_id' => $validated['category_id']
                ]
            );
        });

        return redirect()->route('custody.inventory.index')->with('success', 'تم تحديث عهدة المخزن بنجاح');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                ItemDetails::where('custody_audit_id', $id)->delete();
                InventoryAudit::where('id', $id)->delete();
                CustodyAuditBase::where('id', $id)->delete();
            });

            return redirect()->route('custody.inventory.index')->with('success', 'تم حذف العهدة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('custody.inventory.index')->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    // Details methods remain unchanged...
    public function createDetails($auditId)
    {
        $audit = CustodyAuditBase::with('inventoryDetail')->findOrFail($auditId);
        $quantity = $audit->inventoryDetail->observed_quantity;
        return view('custody.inventory.details', compact('audit', 'quantity'));
    }

    public function storeDetails(Request $request, $auditId)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.serial_no' => 'required|string|distinct|unique:item_details,serial_no',
            'details.*.expiry_date' => 'nullable|date',
        ]);

        foreach ($request->details as $detail) {
            ItemDetails::create([
                'custody_audit_id' => $auditId,
                'serial_no' => $detail['serial_no'],
                'expiry_date' => $detail['expiry_date'] ?? null,
            ]);
        }

        return redirect()->route('custody.inventory.index')->with('success', 'تم إضافة تفاصيل الأصناف بنجاح');
    }
}
