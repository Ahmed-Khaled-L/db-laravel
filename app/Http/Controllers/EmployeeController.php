<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with("department");

        // Search (Name, SSN, Job)
        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("ssn", "like", "%{$search}%")
                    ->orWhere("job_title", "like", "%{$search}%");
            });
        }

        // Filter by Department
        if ($request->filled("department_id")) {
            $query->where("department_id", $request->department_id);
        }

        $employees = $query->orderBy("name")->paginate(20);
        $departments = Department::all();

        // Attach mobile number manually for display
        foreach ($employees as $emp) {
            $emp->mobile = DB::table("employee_mobiles")
                ->where("employee_id", $emp->id)
                ->value("mobile_no");
        }

        return view("master.employees", compact("employees", "departments"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|max:255",
            "ssn" => "required|unique:employees,ssn",
            "job_title" => "required|string|max:255",
            "birth_date" => "required|date",
            "department_id" => "required|exists:departments,id",
            "mobile" => "nullable|string|max:20",
        ]);

        DB::transaction(function () use ($data) {
            $employee = Employee::create([
                "name" => $data["name"],
                "ssn" => $data["ssn"],
                "job_title" => $data["job_title"],
                "birth_date" => $data["birth_date"],
                "department_id" => $data["department_id"],
            ]);

            if (!empty($data["mobile"])) {
                DB::table("employee_mobiles")->insert([
                    "employee_id" => $employee->id,
                    "mobile_no" => $data["mobile"],
                ]);
            }
        });

        return redirect()
            ->route("employees.index")
            ->with("success", "تم إضافة الموظف بنجاح.");
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            "name" => "required|string|max:255",
            "ssn" => [
                "required",
                Rule::unique("employees")->ignore($employee->id),
            ],
            "job_title" => "required|string|max:255",
            "birth_date" => "required|date",
            "department_id" => "required|exists:departments,id",
            "mobile" => "nullable|string|max:20",
        ]);

        DB::transaction(function () use ($data, $employee) {
            $employee->update([
                "name" => $data["name"],
                "ssn" => $data["ssn"],
                "job_title" => $data["job_title"],
                "birth_date" => $data["birth_date"],
                "department_id" => $data["department_id"],
            ]);

            // Update mobile: delete old, insert new
            if (isset($data["mobile"])) {
                DB::table("employee_mobiles")
                    ->where("employee_id", $employee->id)
                    ->delete();
                if (!empty($data["mobile"])) {
                    DB::table("employee_mobiles")->insert([
                        "employee_id" => $employee->id,
                        "mobile_no" => $data["mobile"],
                    ]);
                }
            }
        });

        return redirect()
            ->route("employees.index")
            ->with("success", "تم تحديث بيانات الموظف.");
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()
            ->route("employees.index")
            ->with("success", "تم حذف الموظف.");
    }
}
