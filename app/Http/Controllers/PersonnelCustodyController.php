<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustodyAuditBase;
use App\Models\PersonnelCustodyAudit;
use App\Models\Register;
use App\Models\RegisterPage;
use App\Models\Item;
use App\Models\Employee;
use App\Models\Category;
use App\Models\ItemDetails;

class PersonnelCustodyController extends Controller
{
    // ... index method remains the same ...
    public function index()
    {
        $audits = CustodyAuditBase::where('audit_type', 'Personnel')
            ->with(['personnelDetail.employee', 'item', 'register'])
            ->latest()
            ->get();
        return view('custody.personnel.index', compact('audits'));
    }

    public function create()
    {
        $registers = Register::all();
        $items = Item::all();
        $employees = Employee::all();

        // Pass all categories; we will filter them with JS
        $categories = Category::all();

        // Extract unique Types for the first dropdown
        $types = $categories->pluck('type')->unique();

        return view('custody.personnel.create', compact('registers', 'items', 'employees', 'categories', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'register_id' => 'required|exists:registers,id',
            'page_no' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'unit_price' => 'required|numeric|min:0',
            'employee_id' => 'required|exists:employees,id',
            'category_id' => 'required',
            'category_type' => 'required',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'add_details' => 'nullable|boolean', // Checkbox to add details
        ]);

        $auditId = DB::transaction(function () use ($validated) {

            // 1. Ensure Register Page Exists (Store ID is NULL for Personnel)
            RegisterPage::firstOrCreate(
                [
                    'register_id' => $validated['register_id'],
                    'page_number' => $validated['page_no']
                ],
                [
                    'store_id' => null // Explicitly null as requested
                ]
            );

            // 2. Create Base Audit Record (Date is NOW)
            $base = CustodyAuditBase::create([
                'date' => now(),
                'unit_price' => $validated['unit_price'],
                'register_id' => $validated['register_id'],
                'page_no' => $validated['page_no'],
                'item_id' => $validated['item_id'],
                'audit_type' => 'Personnel',
                'notes' => $validated['notes'],
            ]);

            // 3. Create Personnel Specific Record
            PersonnelCustodyAudit::create([
                'id' => $base->id,
                'employee_id' => $validated['employee_id'],
                'quantity' => $validated['quantity'],
                'category_id' => $validated['category_id'],
                'category_type' => $validated['category_type'],
            ]);

            return $base->id;
        });

        // Redirect based on user choice
        if ($request->has('add_details') && $request->add_details == 1) {
            return redirect()->route('custody.details.create', ['auditId' => $auditId]);
        }

        return redirect()->route('custody.personnel.index')->with('success', 'تم إضافة العهدة بنجاح');
    }

    // --- ITEM DETAILS METHODS ---

    public function createDetails($auditId)
    {
        $audit = CustodyAuditBase::with('personnelDetail')->findOrFail($auditId);
        $quantity = $audit->personnelDetail->quantity;

        return view('custody.personnel.details', compact('audit', 'quantity'));
    }

    public function storeDetails(Request $request, $auditId)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.serial_no' => 'required|string|distinct|unique:item_details,serial_no', // Ensure unique serials
            'details.*.expiry_date' => 'nullable|date',
        ]);

        foreach ($request->details as $detail) {
            ItemDetails::create([
                'custody_audit_id' => $auditId,
                'serial_no' => $detail['serial_no'],
                'expiry_date' => $detail['expiry_date'] ?? null,
            ]);
        }

        return redirect()->route('custody.personnel.index')->with('success', 'تم إضافة تفاصيل الأصناف بنجاح');
    }
}
