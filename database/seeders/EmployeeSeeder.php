<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table("employee_mobiles")->truncate();
        Employee::truncate();
        Schema::enableForeignKeyConstraints();

        $dept = Department::firstOrCreate(["name" => "الإدارة العامة"]);

        $employees = [
            [
                "name" => "أحمد محمد علي",
                "job_title" => "مدير المخازن",
                "ssn" => "29001011234567",
                "birth_date" => "1990-01-01",
                "mobiles" => ["01012345678", "01212345678"],
            ],
            [
                "name" => "سارة محمود حسن",
                "job_title" => "أمين عهده",
                "ssn" => "29205051234567",
                "birth_date" => "1992-05-05",
                "mobiles" => ["01112345678"],
            ],
            [
                "name" => "خالد إبراهيم يوسف",
                "job_title" => "كاتب شطب",
                "ssn" => "28510101234567",
                "birth_date" => "1985-10-10",
                "mobiles" => ["01512345678", "01098765432"],
            ],
        ];

        foreach ($employees as $data) {
            // 1. Create Login User
            $user = User::firstOrCreate(
                ["email" => "emp_" . substr($data["ssn"], -4) . "@example.com"],
                ["name" => $data["name"], "password" => Hash::make("password")],
            );

            // 2. Create Employee
            $employee = Employee::create([
                "name" => $data["name"],
                "ssn" => $data["ssn"],
                "job_title" => $data["job_title"],
                "birth_date" => $data["birth_date"],
                "department_id" => $dept->id,
                "user_id" => $user->id,
            ]);

            // 3. Create Mobiles (NO TIMESTAMPS)
            foreach ($data["mobiles"] as $mobile) {
                DB::table("employee_mobiles")->insert([
                    "employee_id" => $employee->id,
                    "mobile_no" => $mobile,
                    // Do NOT add created_at or updated_at here
                ]);
            }
        }
    }
}
