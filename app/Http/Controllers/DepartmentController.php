<?php

namespace App\Http\Controllers;



use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all(); // The data for the "Excel Sheet"
        return view('master.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dept_name' => 'required|string|max:255',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Row added successfully');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'dept_name' => 'required|string|max:255',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Row updated successfully');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('items.index')->with('success', 'Row deleted.');
    }
}
