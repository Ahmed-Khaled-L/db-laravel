<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register;
use Illuminate\Database\QueryException;

class RegisterController extends Controller
{
    public function index()
    {
        $registers = Register::orderBy('id', 'desc')->get();
        return view("master.registers", compact("registers"));
    }

    public function store(Request $request)
    {
        $request->validate(["register_name" => "required|string|max:255"]);
        Register::create($request->all());
        return redirect()->route("registers.index")->with("success", "تم إضافة السجل بنجاح");
    }

    public function update(Request $request, Register $register)
    {
        $request->validate(["register_name" => "required|string|max:255"]);
        $register->update($request->all());
        return redirect()->route("registers.index")->with("success", "تم تحديث السجل بنجاح");
    }

    public function destroy(Register $register)
    {
        try {
            $register->delete();
            return redirect()->route("registers.index")->with("success", "تم حذف السجل.");
        } catch (QueryException $e) {
            // Error 23000: Integrity constraint violation
            if ($e->getCode() == "23000") {
                return redirect()->route("registers.index")->with("error", "لا يمكن حذف السجل لأنه يحتوي على صفحات أو بيانات مرتبطة.");
            }
            return redirect()->route("registers.index")->with("error", "حدث خطأ أثناء الحذف.");
        }
    }
}
