<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('id', 'desc')->get();
        // dd($departments);
        return view('master.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Department::create($request->all());
        return redirect()->route('departments.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $department->update($request->all());
        return redirect()->route('departments.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return redirect()->route('departments.index')->with('success', 'تم حذف القسم بنجاح');
        } catch (QueryException $e) {
            // Error Code 23000: Integrity constraint violation
            if ($e->getCode() == "23000") {
                return redirect()->route('departments.index')->with('error', 'لا يمكن حذف هذا القسم لأنه مرتبط بموظفين أو سجلات أخرى.');
            }
            return redirect()->route('departments.index')->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}
