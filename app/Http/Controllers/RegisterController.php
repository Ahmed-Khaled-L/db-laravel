<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register;

class RegisterController extends Controller
{
    public function index()
    {
        $registers = Register::all();
        return view("master.registers", compact("registers"));
    }

    public function store(Request $request)
    {
        // FIX: Changed 'reg_name' to 'register_name' to match DB column
        $validated = $request->validate([
            "register_name" => "required|string|max:255",
        ]);

        Register::create($validated);

        return redirect()
            ->route("registers.index")
            ->with("success", "Register added successfully");
    }

    public function update(Request $request, Register $register)
    {
        // FIX: Changed 'reg_name' to 'register_name'
        $validated = $request->validate([
            "register_name" => "required|string|max:255",
        ]);

        $register->update($validated);

        return redirect()
            ->route("registers.index")
            ->with("success", "Register updated successfully");
    }

    public function destroy(Register $register)
    {
        $register->delete();
        return redirect()
            ->route("registers.index")
            ->with("success", "Register deleted.");
    }
}
