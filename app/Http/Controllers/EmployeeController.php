<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department')->get();
        $departments = Department::all(); // For the dropdown
        return view('master.employees', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'ssn' => 'required|unique:employees',
            'job_title' => 'required',
            'birth_date' => 'required|date',
            'department_id' => 'required|exists:departments,id'
        ]);

        Employee::create($data);
        return redirect()->route('employees.index')->with('success', 'Employee added.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required',
            'ssn' => 'required|unique:employees,ssn,' . $employee->id,
            'job_title' => 'required',
            'birth_date' => 'required|date',
            'department_id' => 'required|exists:departments,id'
        ]);

        $employee->update($data);
        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index');
    }
}
