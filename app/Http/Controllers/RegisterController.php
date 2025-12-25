<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Register;


class RegisterController extends Controller
{
    public function index()
    {
        $registers = Register::all(); // The data for the "Excel Sheet"
        return view('master.registers', compact('registers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reg_name' => 'required|string|max:255',
        ]);

        Register::create($validated);

        return redirect()->route('registers.index')->with('success', 'Row added successfully');
    }

    public function update(Request $request, Register $register)
    {
        $validated = $request->validate([
            'reg_name' => 'required|string|max:255',
        ]);

        $register->update($validated);

        return redirect()->route('registers.index')->with('success', 'Row updated successfully');
    }

    public function destroy(Register $register)
    {
        $register->delete();
        return redirect()->route('registers.index')->with('success', 'Row deleted.');
    }
}
