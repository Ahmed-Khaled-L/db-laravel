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
    public function index()
    {
        // Eager load 'itemDetails' so we can pass them to the modal
        $audits = CustodyAuditBase::where('audit_type', 'Personnel')
            ->with(['personnelDetail.employee', 'item', 'register', 'itemDetails'])
            ->latest()
            ->get();
        return view('custody.personnel.index', compact('audits'));
    }

    public function create()
    {
        $registers = Register::all();
        $items = Item::all();
        $employees = Employee::all();
        $categories = Category::all();
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
            'add_details' => 'nullable|boolean',
        ]);

        $auditId = DB::transaction(function () use ($validated) {
            RegisterPage::firstOrCreate(
                [
                    'register_id' => $validated['register_id'],
                    'page_number' => $validated['page_no']
                ],
                [
                    'store_id' => null
                ]
            );

            $base = CustodyAuditBase::create([
                'date' => now(),
                'unit_price' => $validated['unit_price'],
                'register_id' => $validated['register_id'],
                'page_no' => $validated['page_no'],
                'item_id' => $validated['item_id'],
                'audit_type' => 'Personnel',
                'notes' => $validated['notes'],
            ]);

            PersonnelCustodyAudit::create([
                'id' => $base->id,
                'employee_id' => $validated['employee_id'],
                'quantity' => $validated['quantity'],
                'category_id' => $validated['category_id'],
                'category_type' => $validated['category_type'],
            ]);

            return $base->id;
        });

        if ($request->has('add_details') && $request->add_details == 1) {
            return redirect()->route('custody.details.create', ['auditId' => $auditId]);
        }

        return redirect()->route('custody.personnel.index')->with('success', 'تم إضافة العهدة بنجاح');
    }

    public function edit($id)
    {
        $audit = CustodyAuditBase::with(['personnelDetail', 'register', 'item'])->findOrFail($id);

        // Ensure this is a Personnel audit
        if ($audit->audit_type !== 'Personnel') {
            return redirect()->route('custody.personnel.index')->with('error', 'هذا السجل ليس عهدة شخصية');
        }

        $registers = Register::all();
        $items = Item::all();
        $employees = Employee::all();
        $categories = Category::all();
        $types = $categories->pluck('type')->unique();

        return view('custody.personnel.edit', compact('audit', 'registers', 'items', 'employees', 'categories', 'types'));
    }

    public function update(Request $request, $id)
    {
        $audit = CustodyAuditBase::findOrFail($id);

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
        ]);

        DB::transaction(function () use ($validated, $audit) {

            // 1. Ensure Register Page Exists
            RegisterPage::firstOrCreate(
                [
                    'register_id' => $validated['register_id'],
                    'page_number' => $validated['page_no']
                ],
                [
                    'store_id' => null
                ]
            );

            // 2. Update Base Record
            $audit->update([
                'unit_price' => $validated['unit_price'],
                'register_id' => $validated['register_id'],
                'page_no' => $validated['page_no'],
                'item_id' => $validated['item_id'],
                'notes' => $validated['notes'],
            ]);

            // 3. Update Personnel Specific Record
            $audit->personnelDetail()->update([
                'employee_id' => $validated['employee_id'],
                'quantity' => $validated['quantity'],
                'category_id' => $validated['category_id'],
                'category_type' => $validated['category_type'],
            ]);
        });

        return redirect()->route('custody.personnel.index')->with('success', 'تم تحديث العهدة بنجاح');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Delete related Item Details first (if any)
                ItemDetails::where('custody_audit_id', $id)->delete();

                // Delete the sub-table entry
                PersonnelCustodyAudit::where('id', $id)->delete();

                // Delete the base table entry
                CustodyAuditBase::where('id', $id)->delete();
            });

            return redirect()->route('custody.personnel.index')->with('success', 'تم حذف العهدة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('custody.personnel.index')->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

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

        return redirect()->route('custody.personnel.index')->with('success', 'تم إضافة تفاصيل الأصناف بنجاح');
    }


    public function storeSingleDetail(Request $request, $auditId)
    {
        $request->validate([
            'serial_no' => 'required|string|distinct|unique:item_details,serial_no',
            'expiry_date' => 'nullable|date',
        ]);

        ItemDetails::create([
            'custody_audit_id' => $auditId,
            'serial_no' => $request->serial_no,
            'expiry_date' => $request->expiry_date,
        ]);

        return back()->with('success', 'تم إضافة تفاصيل الصنف بنجاح');
    }

    public function updateSingleDetail(Request $request)
    {
        // We use 'original_serial_no' to find the record because serial_no is the Primary Key
        $originalSerial = $request->input('original_serial_no');

        $request->validate([
            'original_serial_no' => 'required|exists:item_details,serial_no',
            'serial_no' => 'required|string',
            'expiry_date' => 'nullable|date',
        ]);

        $detail = ItemDetails::where('serial_no', $originalSerial)->firstOrFail();

        // Check uniqueness if serial changed
        if ($request->serial_no !== $originalSerial) {
            if (ItemDetails::where('serial_no', $request->serial_no)->exists()) {
                return back()->with('error', 'السيريال الجديد موجود بالفعل لمنتج آخر.');
            }
        }

        DB::transaction(function () use ($detail, $request, $originalSerial) {
            // Since PK can't easily be updated, if it changes, we create new and delete old
            if ($request->serial_no !== $originalSerial) {
                ItemDetails::create([
                    'custody_audit_id' => $detail->custody_audit_id,
                    'serial_no' => $request->serial_no,
                    'expiry_date' => $request->expiry_date,
                ]);
                $detail->delete();
            } else {
                $detail->update([
                    'expiry_date' => $request->expiry_date,
                ]);
            }
        });

        return back()->with('success', 'تم تحديث التفاصيل بنجاح');
    }

    public function destroySingleDetail(Request $request)
    {
        $serial = $request->input('serial_no');
        ItemDetails::where('serial_no', $serial)->delete();
        return back()->with('success', 'تم حذف التفاصيل بنجاح');
    }
}
